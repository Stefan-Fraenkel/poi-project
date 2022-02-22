<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Stevebauman\Location\Facades\Location;


class POIController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $filters = ['categories', 'rating', 'distance'];

    /*
     * to add a filter write a function that accepts a request and an array as parameters and returns an array
     * standard value for array should be "null"
     *      example: private function nameFilter(Request $request, $output=null): array
     * then add the part of its name before "Filter" in the array $filters
     *      example: $filters = [ 'filter1', 'filter2', 'name']
     */

    public function index($results = null, $category = 'Alle')
    {
        //results muss ein Array sein mit Objekten, die die Eigenschaft poi_id besitzen muessen
        if ($results == null) {
            $query = 'select * from pois';
            $results = DB::select($query);
        }
        $outputs = array();
        foreach ($results as $result) {
            $entry = $this->getShortPOI($result->poi_id);
            array_push($outputs, $entry);
        }
        return view('poi.index')->with('pois', $outputs)->with('category', $category);
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $colval = $this->getInsertColVal($request);
            $query = 'INSERT INTO pois (' . $colval['columns'] . ') VALUES (' . $colval['values'] . ')';
            DB::unprepared($query);
            $query = 'select * from pois where poi_name = "' . $request->poi_name . '"';
            $result = DB::select($query);
            return $this->index($result, $result[0]->poi_name);
        } else return view('poi.create');
    }

    public function show($poi_id) {
        return view('poi.detail')->with('poi', $this->getLongPOI($poi_id));
    }

    public function userPOI()
    {
        $query = 'select * from pois JOIN user_has_poi_ratings ON pois.poi_id = user_has_poi_ratings.poi_id where user_has_poi_ratings.user_id = "' . Auth::user()->id . '"';
        $results = DB::select($query);
        return $this->index($results, 'Von ' . Auth::user()->name . ' bewertet');
    }


    public function update(Request $request)
    {
        if($request->isMethod('post')) {
            $query = 'update pois set ' . $this->getUpdateColVal($request) . ' where poi_id = "' . $request->poi_id . '"';
            DB::unprepared($query);
            return $this->index();
        }
        else {
            $poi_id = explode('/', $request->getRequestUri());
            $poi_id = end($poi_id);
            $query = 'select * from pois where poi_id = "' . $poi_id . '"';
            $result = DB::select($query);
            return view('poi.update')->with('poi', $result[0] );
        }
    }

    public function ratePOI(Request $request)
    {
        if($request->isMethod('post')) {
            $user_id = Auth::user()->id;
            $score = $request->score;
            $comment = $request->comment;
            $poi_id = $request->poi_id;
            if ($comment) {
                $query = 'INSERT INTO user_has_poi_ratings (user_id, poi_id, score, comment) VALUES ("' . $user_id . '", "' . $poi_id . '", "' . $score . '", "' . $comment . '")';
            }
            else $query = 'INSERT INTO user_has_poi_ratings (user_id, poi_id, score) VALUES ("' . $user_id . '", "' . $poi_id . '", "' . $score . '")';
            DB::unprepared($query);
            $query = 'select * from pois where poi_id = "' . $request->poi_id . '"';
            $result = DB::select($query);
            return $this->index($result, $result[0]->poi_name);
        }
        else {
            $poi_id = explode('/', $request->getRequestUri());
            $poi_id = end($poi_id);
            $query = 'select * from pois where poi_id = "' . $poi_id . '"';
            $result = DB::select($query);
            return view('poi.rate')->with('poi', $result ); //view still needs to be created
        }
    }

    public function destroy(Request $request) //implement in view href=poi/delete/$poi_id for poi delete button -> best place: show me more view of poi
    {
        $poi_id = explode('/', $request->getRequestUri());
        $poi_id = end($poi_id);
        $query = 'delete from pois where poi_id = "' . $poi_id . '"';
        $result = DB::select($query);
        return $this->index();
    }

    public function categoryIndex() // to dynamically fill the category dropdown with categories from database
    {
        $query = 'select * from poi_categories';
        $categories = DB::select($query);

        $output = array();
        foreach ($categories as $category) {
            $output[] = $category->cat_name;
        }

        return view('poi.category')->with('categories', $output);
    }

    public function searchPOIs(Request $request)
    {
        $output = array();
        foreach ($this->filters as $filter) {
            $filterFunction = $filter . 'Filter';
            if ($request->$filter) {
                if (!$output) {
                    $output = $this->arrayMergeUnique($output, $this->{$filterFunction}($request));
                } else {
                    $output = $this->arrayFilter($output, $this->{$filterFunction}($request, $output));
                }
            }
        }
        if ($output) {
            return $this->index($output, 'Suche');
        } else return $this->categoryIndex();
    }

    private function categoriesFilter(Request $request, $output = null): array
    {
        $resultsCategory = array();
        if ($output) {
            foreach ($request->categories as $category) {
                foreach ($output as $entry) {
                    $query = 'select * from pois JOIN poi_has_categories ON pois.poi_id = poi_has_categories.poi_id JOIN poi_categories ON poi_has_categories.cat_id = poi_categories.cat_id  where poi_categories.cat_name = "' . $category . '" and pois.poi_id = "' . $entry->poi_id . '"';
                    $results = DB::select($query);
                    $resultsCategory = $this->arrayMergeUnique($resultsCategory, $results);
                }
            };
        } else {
            foreach ($request->categories as $category) {
                $query = 'select * from pois JOIN poi_has_categories ON pois.poi_id = poi_has_categories.poi_id JOIN poi_categories ON poi_has_categories.cat_id = poi_categories.cat_id  where poi_categories.cat_name = "' . $category . '"';
                $results = DB::select($query);
                $resultsCategory = $this->arrayMergeUnique($resultsCategory, $results);
            };
        }
        return $resultsCategory;
    }

    private function ratingFilter(Request $request, $output = null): array
    {
        $resultsRating = array();
        if ($output) {
            $results = $output;
        } else {
            $query = 'select distinct (poi_id) from user_has_poi_ratings';
            $results = DB::select($query);
        }
        foreach ($results as $result) { //idea for improvement: via in reduce query amount to 1 by setting its content with one variable containing all ids

            $rating = $this->calculateRating($result->poi_id);
            if ($rating >= $request->rating) {
                $query = 'select * from pois JOIN user_has_poi_ratings ON pois.poi_id = user_has_poi_ratings.poi_id WHERE pois.poi_id = ' . $result->poi_id;
                $reply = DB::select($query);
                $resultsRating = $this->arrayMergeUnique($resultsRating, $reply);
            }
        }
        return $resultsRating;
    }

    private function distanceFilter(Request $request, $output = null): array
    {
        /* real solution:
         * $position = Location::get($this->getIp()); -> but won't work on local server
         * $longitude = $position->longitude;
         * $latitude = $position->latitude;
         */

        //hardcoded bs1 Kempten:

        $longitude = 10.317022068768733;
        $latitude = 47.71998328790986;
        $distance = $request->distance;
        if (!is_numeric($distance)) {
            return $output;
        } else {
            $query = 'SELECT poi_id, ROUND((acos(cos(radians(' . $latitude . '))* cos(radians( lat ))* cos(radians( ' . $longitude . ') - radians( pois.long )) + sin(radians( ' . $latitude . ')) * sin(radians( lat )))) * 6371, 1) AS distance FROM pois HAVING distance <= ' . $distance . ';'; //https://en.wikipedia.org/wiki/Great-circle_distance; https://stackoverflow.com/questions/574691/mysql-great-circle-distance-haversine-formula
            return DB::select($query);
        }
    }

    private function arrayMergeUnique($base_array, $add_array): array
    {
        foreach ($add_array as $key => $value) { //remove duplicates from add_array
            foreach ($add_array as $remove_key => $remove_value) {
                if ($remove_value->poi_id == $value->poi_id && !$remove_key == $key) {
                    unset($add_array[$key]);
                }
            }
        }

        if ($base_array) {
            foreach ($add_array as $key => $value) {
                foreach ($base_array as $base_value) {
                    if ($base_value->poi_id == $value->poi_id) {
                        unset($add_array[$key]);
                    }
                }
            }

            return array_merge_recursive($base_array, $add_array);

        } else {
            return $add_array;
        }
    }

    private function arrayFilter($base_array, $filter_array): array // remove everything from base_array which is not in filter_array
    {
        foreach ($base_array as $key => $value) {
            $check = false;
            foreach ($filter_array as $add_value) {
                if ($value->poi_id == $add_value->poi_id) {
                    $check = true;
                }
            }
            if ($check == false) {
                unset($base_array[$key]);
            }
        }
        return $base_array;
    }

    private function calculateRating($poi_id)
    {
        $divisor = $this->countScores($poi_id);
        $query = ('select SUM(user_has_poi_ratings.score)/' . $divisor . ' AS rating from pois join user_has_poi_ratings on pois.poi_id = user_has_poi_ratings.poi_id where user_has_poi_ratings.poi_id = ' . $poi_id);
        $results = DB::select($query);
        return $results[0]->rating;
    }

    private function getLongPOI ($poi_id)
    {
        $divisor = $this->countScores($poi_id);

        /* real solution:
         * $position = Location::get($this->getIp()); -> but won't work on local server
         * $longitude = $position->longitude;
         * $latitude = $position->latitude;
         */

        //hardcoded bs1 Kempten:

        $longitude = 10.317022068768733;
        $latitude = 47.71998328790986;
        $query = 'SELECT pois.poi_id, pois.poi_name, pois.street, pois.zipcode, pois.city, pois.description, pois.open, pois.website, pois.photo, pois.long, pois.lat, poi_categories.cat_id, poi_categories.cat_name, user_has_poi_ratings.score, user_has_poi_ratings.comment, users.name AS user_name, users.id AS user_id,
        SUM(user_has_poi_ratings.score)/' . $divisor . ' AS rating, ROUND((acos(cos(radians(' . $latitude . '))* cos(radians( lat ))* cos(radians( ' . $longitude . ') - radians( pois.long )) + sin(radians( ' . $latitude . ')) * sin(radians( lat )))) * 6371, 1) AS distance
        FROM pois
        RIGHT JOIN user_has_poi_ratings ON pois.poi_id = user_has_poi_ratings.poi_id
        RIGHT JOIN poi_has_categories ON pois.poi_id = poi_has_categories.poi_id
        RIGHT JOIN poi_categories ON poi_has_categories.cat_id = poi_categories.cat_id
        RIGHT JOIN users ON user_has_poi_ratings.user_id = users.id
        WHERE pois.poi_id = ' . $poi_id . '
        GROUP BY poi_categories.cat_id, pois.poi_id, user_has_poi_ratings.score, user_has_poi_ratings.comment, users.name, users.id';

        $results = DB::select($query);
        $reply = $results[0];
        $reply->cat_names = array();
        $reply->users = array();

        $i = 0;

        foreach ($results as $result) {

            $reply->users[$result->user_id] = ['id' => $result->user_id, 'name' => $result->user_name, 'comment' => $result->comment, 'rating' => $result->rating];

            $user_photo = User::where('id', $result->user_id) ->first()->profile_photo_path;

            if ($user_photo) {
                $reply->users[$result->user_id]['photo'] = '/storage/' . $user_photo;
            }
            else $reply->users[$result->user_id]['photo'] = 'https://ui-avatars.com/api/?name=' . $result->user_name . '&color=7F9CF5&background=EBF4FF';

            if (!in_array($result->cat_name, $reply->cat_names))
            {
                $reply->cat_names[] = $result->cat_name;
            }

            $i++;

        }

        unset($reply->cat_name, $reply->cat_id, $reply->user_id, $reply->rating, $reply->user_name, $reply->comment);
        return $reply;
    }

    private function getShortPOI($poi_id)
    {
        $divisor = $this->countScores($poi_id);

        /* real solution:
         * $position = Location::get($this->getIp()); -> but won't work on local server
         * $longitude = $position->longitude;
         * $latitude = $position->latitude;
         */

        //hardcoded bs1 Kempten:

        $longitude = 10.317022068768733;
        $latitude = 47.71998328790986;
        $query = 'select pois.poi_id, pois.poi_name, pois.description, pois.photo, SUM(user_has_poi_ratings.score)/' . $divisor . ' AS rating, ROUND((acos(cos(radians(' . $latitude . '))* cos(radians( lat ))* cos(radians( ' . $longitude . ') - radians( pois.long )) + sin(radians( ' . $latitude . ')) * sin(radians( lat )))) * 6371, 1) AS distance from pois RIGHT JOIN user_has_poi_ratings ON pois.poi_id = user_has_poi_ratings.poi_id WHERE pois.poi_id = ' . $poi_id;
        $reply = DB::select($query);
        return $reply[0];
    }

    private function getInsertColVal(Request $request): array
    {
        $data = $request->all();
        $columns = "";
        $values = "";
        foreach ($data as $key => $value) {
            if ($value && $key != '_token' && $key != 'poi_id') {
                $columns .= $key . ', ';
                $values .= '"' . $value . '", ';
            }
        }
        $columns = rtrim ( $columns , ', ');
        $values = rtrim ( $values , ', ');
        return ['columns' => $columns, 'values' => $values];
    }

    private function countScores ($poi_id) {
        $query = 'select COUNT(*) AS divisor from user_has_poi_ratings where poi_id = ' . $poi_id;
        $result = DB::select($query);
        return $result[0]->divisor;
    }

    private function getUpdateColVal(Request $request): string
    {
        $data = $request->all();
        $update = "";
        foreach ($data as $key => $value) {
            if ($value && $key != '_token' && $key != 'poi_id') {
                $update .= 'pois.' . $key . ' = "' . $value .'", ';
            }
        }
        return rtrim ( $update , ', ');
    }

    private function getIp(): ?string
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return request()->ip(); // it will return server ip when no client ip found
    }

    public function initialSetup () {

        /* new new:

        $entries = [['name' => 'Stefan Fränkel', 'email' => 'stefan@genxtreme.de', 'password' => '1234'], ['name' => 'Theresa Schwarzmann', 'email' => 'Theresa-Sch@t-online.de', 'password' => '1234'], ['name' => 'Tom Test', 'email' => 'Tom@test.de', 'password' => '1234'], ['name' => 'Max Mustermann', 'email' => 'Max@muster.de', 'password' => '1234'], ['name' => 'Klaus Probieren', 'email' => 'klaus@probieren.de', 'password' => '1234']];

        foreach ($entries as $entry) {
            $user = new User();
            $user->name = $entry['name'];
            $user->email = $entry['email'];
            $user->password = Hash::make($entry['password']);
            $user->save();
        }

        DB::unprepared('INSERT INTO pois (poi_name, street, zipcode, city, description, open, website, photo, pois.long, lat) VALUES ("Pizzeria Gargano", "Badeweg 3", 87435, "Kempten", "Nette Pizzaria, mit kleiner Sonnenterrasse...", "Täglich 12:00 Uhr bis 21:00 Uhr", "https://pizza-gargano.de/", "https://images.pexels.com/photos/905847/pexels-photo-905847.jpeg?auto=compress&cs=tinysrgb&dpr=3&h=750&w=1260", 10.326699, 47.717592), ("Park Theater", "Seeweg 5", 87435, "Kempten", "Nachtclub mit wechselnden DJs und Happy-Hour von 23:00 Uhr - 24:00 Uhr", "Freitag + Samstag von 22:00 Uhr bis 05:00 Uhr", "https://parktheater.de/", "https://images.pexels.com/photos/2114365/pexels-photo-2114365.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260", 10.246387, 47.746683 ), ("Naruto Sushi", "Wengen 4", 87435, "Kempten", "Sushi Spezialitäten aus hochqualitativem Fisch.", "Dienstag bis Sonntag von 11:00 Uhr bis 22:00 Uhr", "https://naruto-sushi.de/", "https://images.pexels.com/photos/2098085/pexels-photo-2098085.jpeg?auto=compress&cs=tinysrgb&dpr=3&h=750&w=1260", 10.290895, 47.738662), ("Fitness Park Dream Fit", "Sportstrasse 21", 87435, "Kempten", "Moderner Fitnesspark mit vielen Geräten und professionellem Team.", "Täglich von 06:00 Uhr bis 23:00 Uhr", "https://dream-fit.de/", "https://images.pexels.com/photos/1954524/pexels-photo-1954524.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260", 10.306763, 47.663605), ("Kinder Kletterpark", "Rattenweg 56", 87435, "Kempten", "Indoor Klettergarten für Kinder mit Selbstbedienungs Restaurant.", "Donnerstag bis Sonntag von 10:00 Uhr bis 18:00 Uhr", "https://kletterpark-hoch-hinaus.de/", "https://images.pexels.com/photos/5383729/pexels-photo-5383729.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260", 10.736084, 47.555527)');
        DB::unprepared('INSERT INTO poi_categories (cat_name) VALUES ("Restaurant"), ("Sport"), ("Nachtleben"), ("Einkaufen"), ("Erleben")');
        DB::unprepared('INSERT INTO user_has_poi_ratings (user_id, poi_id, score, comment) VALUES (1,4,4.5, "tolle aussicht"), (1,3,4.0, "gutes Essen"), (2,4,1.5, "schlechter Service"), (3,2,5, "Alles top, gerne Wieder"), (4,1,4.5, "mega Stimmung und nettes Personal"), (5,5,5, "war oke"), (4,3,5, "geeignet für Familien")');

        DB::unprepared('INSERT INTO poi_has_categories (poi_id, cat_id) VALUES (1, 1), (3, 1)');
        DB::unprepared('INSERT INTO poi_has_categories (poi_id, cat_id) VALUES (4, 2), (5, 2)');
        DB::unprepared('INSERT INTO poi_has_categories (poi_id, cat_id) VALUES (2, 3), (3, 3)');
        DB::unprepared('INSERT INTO poi_has_categories (poi_id, cat_id) VALUES (4, 4)');
        DB::unprepared('INSERT INTO poi_has_categories (poi_id, cat_id) VALUES (2, 5), (5, 5)');

        */

        return view('dashboard');

    }

}

/*
 * alternative ratings filter:
 *
 *     public function filterForEach(Request $request)
    {

        $query = 'select distinct (poi_id) from user_has_poi_ratings';
        $results = DB::select($query);
        $outputs = array();
        foreach ($results as $result) {
            $input = $this->filterRatings($result->poi_id);
            if ($input[0]->durchschnittsbewertung >= $request->rating) {
                array_push($outputs, $input[0]);
            }
        }
        return $this->index($outputs, "Sternebewertung");
    }

    public function filterRatings($poiid): array
    {

        $filterRating = 'select COUNT(*) AS number from user_has_poi_ratings where poi_id = ' . $poiid;
        $divisor = DB::select($filterRating);
        $divisor = $divisor[0]->number;
        $query = ('select pois.poi_id, SUM(user_has_poi_ratings.score)/' . $divisor . ' AS durchschnittsbewertung from pois join user_has_poi_ratings on pois.poi_id = user_has_poi_ratings.poi_id where user_has_poi_ratings.poi_id = ' . $poiid);
        $results = DB::select($query);
        $durchschnitt = $results[0]->durchschnittsbewertung;
        //dd($results);
        return $results;
    }
 */
