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
use function PHPUnit\Framework\returnValueMap;


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

    public function initialSetup () {

        DB::unprepared('INSERT INTO pois (poi_name, street, zipcode, city, description, open, website, photo, pois.long, lat) VALUES ("Pizzeria Gargano", "Badeweg 3", 87435, "Kempten", "Nette Pizzaria, mit kleiner Sonnenterrasse...", "Täglich 12:00 Uhr bis 21:00 Uhr", "https://pizza-gargano.de/", "https://images.pexels.com/photos/905847/pexels-photo-905847.jpeg?auto=compress&cs=tinysrgb&dpr=3&h=750&w=1260", 10.326699, 47.717592), ("Park Theater", "Seeweg 5", 87435, "Kempten", "Nachtclub mit wechselnden DJs und Happy-Hour von 23:00 Uhr - 24:00 Uhr", "Freitag + Samstag von 22:00 Uhr bis 05:00 Uhr", "https://parktheater.de/", "https://images.pexels.com/photos/2114365/pexels-photo-2114365.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260", 10.246387, 47.746683 ), ("Naruto Sushi", "Wengen 4", 87435, "Kempten", "Sushi Spezialitäten aus hochqualitativem Fisch.", "Dienstag bis Sonntag von 11:00 Uhr bis 22:00 Uhr", "https://naruto-sushi.de/", "https://images.pexels.com/photos/2098085/pexels-photo-2098085.jpeg?auto=compress&cs=tinysrgb&dpr=3&h=750&w=1260", 10.290895, 47.738662), ("Fitness Park Dream Fit", "Sportstrasse 21", 87435, "Kempten", "Moderner Fitnesspark mit vielen Geräten und professionellem Team.", "Täglich von 06:00 Uhr bis 23:00 Uhr", "https://dream-fit.de/", "https://images.pexels.com/photos/1954524/pexels-photo-1954524.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260", 10.306763, 47.663605), ("Kinder Kletterpark", "Rattenweg 56", 87435, "Kempten", "Indoor Klettergarten für Kinder mit Selbstbedienungs Restaurant.", "Donnerstag bis Sonntag von 10:00 Uhr bis 18:00 Uhr", "https://kletterpark-hoch-hinaus.de/", "https://images.pexels.com/photos/5383729/pexels-photo-5383729.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260", 10.736084, 47.555527)');


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


        /* new:
        DB::unprepared('CREATE TABLE poiTable (poiID int primary key auto_increment, name varchar(100), strasse varchar(50), plz int, ort varchar(50), beschreibung varchar(200), oeffnungszeiten varchar (100), website varchar(100), foto varchar(2048))');
        DB::unprepared('CREATE TABLE kategorienTable (kategorienID int primary key auto_increment, kategorienName varchar(100))');
        DB::unprepared('CREATE TABLE poiKategorienTable (poiID int, kategorienID int, foreign key (poiID) references poiTable(poiID), foreign key (kategorienID) references kategorienTable(kategorienID))');
        //DB::unprepared('CREATE TABLE nutzerTable (nutzerID int primary key auto_increment, benutzername varchar(10), passwort varchar(10), avatar varchar(2048), interneNotiz varchar (400),  admin boolean)');
        DB::unprepared('CREATE TABLE userpoitable (userID int, poiID int, foreign key (userID) references users(id), foreign key (poiID) references poiTable(poiID), bewertung float, referenz varchar(100))');

        DB::unprepared('INSERT INTO poiTable (name, strasse, plz, ort, beschreibung, oeffnungszeiten, website, foto) VALUES ("Pizzaria Gargano", "Badeweg 3", 10997, "Berlin", "Nette Pizzaria, mit kleiner Sonnenterrasse...", "Täglich 12:00 Uhr bis 21:00 Uhr", "https://pizza-gargano.de/", "https://images.pexels.com/photos/905847/pexels-photo-905847.jpeg?auto=compress&cs=tinysrgb&dpr=3&h=750&w=1260"), ("Park Theater", "Seeweg 5", 10997, "Berlin", "Nachtclub mit wechselnden DJs und Happy-Hour von 23:00 Uhr - 24:00 Uhr", "Freitag + Samstag von 22:00 Uhr bis 05:00 Uhr", "https://parktheater.de/", "https://images.pexels.com/photos/2114365/pexels-photo-2114365.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260"), ("Naruto Sush", "Wengen 4", 10997, "Berlin", "Sushi Spezialitäten aus hochqualitativem Fisch.", "Dienstag bis Sonntag von 11:00 Uhr bis 22:00 Uhr", "https://naruto-sushi.de/", "https://images.pexels.com/photos/2098085/pexels-photo-2098085.jpeg?auto=compress&cs=tinysrgb&dpr=3&h=750&w=1260"), ("Fitness Park Dream Fit", "Sportstrasse 21", 10997, "Berlin", "Moderner Fitnesspark mit vielen Geräten und professionellem Team.", "Täglich von 06:00 Uhr bis 23:00 Uhr", "https://dream-fit.de/", "https://images.pexels.com/photos/1954524/pexels-photo-1954524.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260"), ("Kinder Kletterpark", "Rattenweg 56", 10997, "Berlin", "Indoor Klettergarten für Kinder mit Selbstbedienungs Restaurant.", "Donnerstag bis Sonntag von 10:00 Uhr bis 18:00 Uhr", "https://kletterpark-hoch-hinaus.de/", "https://images.pexels.com/photos/5383729/pexels-photo-5383729.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260")');
        DB::unprepared('INSERT INTO kategorienTable (kategorienName) VALUES ("Restaurant"), ("Sport"), ("Nachtleben"), ("Einkaufen"), ("Erleben")');
        //DB::unprepared('INSERT INTO nutzerTable (benutzername, passwort, admin) VALUES ("stefan", "asf45s8e", true), ("theresa", "saohf35", true), ("testMann", "aaf435", false), ("bernd", "agd98d#", false), ("susi", "aojf354", false)');
        DB::unprepared('INSERT INTO nutzerPoiTable (nutzerID, poiID, bewertung, referenz) VALUES (1,4,4.5, "tolle aussicht"), (1,3,6.0, "gutes Essen"), (2,4,1.5, "schlechter Service"), (3,2,10.0, "Alles top, gerne Wieder"), (4,1,8.5, "mega Stimmung und nettes Personal"), (5,5,5.5, "war oke"), (5,3,7.0, "geeignet für Familien")');

        $entries = [['name' => 'Stefan Fränkel', 'email' => 'stefan@genxtreme.de', 'password' => '1234'],['name' => 'Theresa Schwarzmann', 'email' => 'Theresa@test.de', 'password' => '1234'], ['name' => 'Tom Test', 'email' => 'Tom@test.de', 'password' => '1234'], ['name' => 'Max Mustermann', 'email' => 'Max@muster.de', 'password' => '1234'], ['name' => 'Klaus Probieren', 'email' => 'klaus@probieren.de', 'password' => '1234']];

        foreach ($entries as $entry) {
            $user = new User();
            $user->name = $entry['name'];
            $user->email = $entry['email'];
            $user->password = Hash::make($entry['password']);
            $user->save();
        }

        DB::unprepared('ALTER TABLE poitable ADD COLUMN lat DOUBLE(9,6)');
        DB::unprepared('ALTER TABLE poitable ADD COLUMN lng DOUBLE(9,6)');

        DB::unprepared('INSERT INTO poikategorienTable (poiID, kategorienID) VALUES (1, 1), (3, 1)');
        DB::unprepared('INSERT INTO poikategorienTable (poiID, kategorienID) VALUES (4, 2), (5, 2)');
        DB::unprepared('INSERT INTO poikategorienTable (poiID, kategorienID) VALUES (2, 3), (3, 3)');
        DB::unprepared('INSERT INTO poikategorienTable (poiID, kategorienID) VALUES (4, 4)');
        DB::unprepared('INSERT INTO poikategorienTable (poiID, kategorienID) VALUES (2, 5), (5, 5)');
        */

        /* old:
         *
         *  //  DB::unprepared('CREATE TABLE poiTable (poiID int primary key auto_increment, name varchar(100), strasse varchar(50), plz int, ort varchar(50), beschreibung varchar(200), oeffnungszeiten varchar (100), website varchar(100), foto varchar(2048))');

        //   DB::unprepared('CREATE TABLE poiKategorienTable (poiID int, kategorienID, foreign key (poiID) references poiTable(poiID), foreign key (kategorienID) references kategorienTable(kategorienID))');
      // DB::unprepared('INSERT INTO poiTable (name, strasse, plz, ort, beschreibung, oeffnungszeiten, website, foto) VALUES ("Pizzaria Gargano", "Badeweg 3", 10997, "Berlin", "Nette Pizzaria, mit kleiner Sonnenterrasse...", "Täglich 12:00 Uhr bis 21:00 Uhr", "https://pizza-gargano.de/", "https://pixabay.com/get/g03ff87602d3b4ae59a703f1a366df45fd9e15ff80cef4d686bf71cffa7b5d87d6bdd463b7fd7bbc4192a81e40abb01e4a23b8e252c888775022cf1987ad70695fe9b325cf6368f29046dd93e3a8f7c31_1920.jpg"), ("Park Theater", "Seeweg 5", 10997, "Berlin", "Nachtclub mit wechselnden DJs und Happy-Hour von 23:00 Uhr - 24:00 Uhr", "Freitag + Samstag von 22:00 Uhr bis 05:00 Uhr", "https://parktheater.de/", "https://pixabay.com/get/gc9f5de65d74d6060018bc414ed63ca9f11bf67697b3f3fe07fe88061bd0a978c566ccc25b92d00637e19b74850067cb231e46337e0ce7ecded794588f28de5b1ca71ed6afa7b5a75e797ec6c776b6708_1920.jpg"), ("Naruto Sush", "Wengen 4", 10997, "Berlin", "Sushi Spezialitäten aus hochqualitativem Fisch.", "Dienstag bis Sonntag von 11:00 Uhr bis 22:00 Uhr", "https://naruto-sushi.de/", "https://pixabay.com/get/g91fc780d0aa900869ace998001b578fc4fd5473938bb4e5e333316eed01e5fb7d09d42e1474d347f19399e0c0b38e7f9fb5b20e4814a5918b546cf8149fd11b6d000938468710e769aa25e01e40ccf27_1920.jpg"), ("Fitness Park Dream Fit", "Sportstrasse 21", 10997, "Berlin", "Moderner Fitnesspark mit vielen Geräten und professionellem Team.", "Täglich von 06:00 Uhr bis 23:00 Uhr", "https://dream-fit.de/", "https://pixabay.com/get/g632156ce3ca54ebe949a44d4cf247fd91a3be982a366b04593b04ec36a4f8a718460a842e458abad0525f3b436179d9cfb32b0fb640c2fcd7b1d2ae6a59b58eb6bc7ac23dcdb1541637d78948fb66099_1920.jpg"), ("Kinder Kletterpark", "Rattenweg 56", 10997, "Berlin", "Indoor Klettergarten für Kinder mit Selbstbedienungs Restaurant.", "Donnerstag bis Sonntag von 10:00 Uhr bis 18:00 Uhr", "https://kletterpark-hoch-hinaus.de/", "https://pixabay.com/get/ga96056fce6f4c94a0febe01ea1e76144d544076f1f5972bb1f5c6a8602b87a70ec8e052f0ce44dc6d7f976421f78e0b445282556c681cf1e8e9e182e942fe1b721b3c139bea28b9363cfe245bd38129f_1920.jpg")');
       // DB::unprepared('INSERT INTO kategorienTable (kategorienName) VALUES ("Restaurant"), ("Sport"), ("Nachtleben"), ("Einkaufen"), ("Erleben")');
       // DB::unprepared('INSERT INTO nutzerTable (benutzername, passwort, admin) VALUES ("stefan", "asf45s8e", true), ("theresa", "saohf35", true), ("testMann", "aaf435", false), ("bernd", "agd98d#", false), ("susi", "aojf354", false)');
     //   DB::unprepared('CREATE TABLE nutzerPoiTable (nutzerID int, poiID int, foreign key (nutzerID) references nutzerTable(nutzerID), foreign key (poiID) references poiTable(poiID), bewertung float, referenz varchar(100))');

      //  DB::unprepared('INSERT INTO nutzerPoiTable (nutzerID, poiID, bewertung, referenz) VALUES (1,4,4.5, "tolle aussicht"), (1,3,6.0, "gutes Essen"), (2,4,1.5, "schlechter Service"), (3,2,10.0, "Alles top, gerne Wieder"), (4,1,8.5, "mega Stimmung und nettes Personal"), (5,5,5.5, "war oke"), (5,3,7.0, "geeignet für Familien")');
        //DB::unprepared('CREATE TABLE poiTable (poiID int primary key auto_increment, name varchar(100), strasse varchar(50), plz int, ort varchar(50), beschreibung varchar(200), oeffnungszeiten varchar (100), website varchar(100))');

       // DB::unprepared('CREATE TABLE poiTable (poiID int primary key auto_increment, name varchar(100), strasse varchar(50), plz int, ort varchar(50), beschreibung varchar(200), oeffnungszeiten varchar (100), website varchar(100))');
      //  DB::unprepared('CREATE TABLE kategorienTable (kategorienID int primary key auto_increment, kategorienName varchar(100))');
      // DB::unprepared('CREATE TABLE poiKategorienTable (poiID int, kategorienID int, foreign key (poiID) references poiTable(poiID), foreign key (kategorienID) references kategorienTable(kategorienID))');
     //   DB::unprepared('CREATE TABLE nutzerTable (nutzerID int primary key auto_increment, benutzername varchar(10), passwort varchar(10), avatar varchar(2048), interneNotiz varchar (400),  admin boolean)');


         *
         */


        //  DB::unprepared('CREATE TABLE userpoitable (userID bigint default null, poiID int, foreign key (poiID) references poiTable(poiID), bewertung float, referenz varchar(100))');


        return view('dashboard');

    }

    public function filterForEach(Request $request)
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


    public function index($results = null, $category = 'Alle')
    {
        //results muss ein Array sein mit Objekten, die die Eigenschaft poi_id besitzen muessen
        if ($results == null) {
            $query = 'select * from pois';
            $results = DB::select($query);
        }
        $outputs = array();
        foreach ($results as $result) {
            $entry = $this->getshortPOI($result->poi_id);
            array_push($outputs, $entry[0]);
        }
        return view('poi.index')->with('pois', $outputs)->with('category', $category);
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $query = 'INSERT INTO pois (poi_name, street, zipcode, city, description, open, website, photo, pois.long, lat) VALUES ("' . $request->poi_name . '", "' . $request->street . '", "' . $request->zipcode . '", "' . $request->city . '", "' . $request->description . '", "' . $request->openingHours . '", "' . $request->website . '", "' . $request->photo . $request->long . '", "' . $request->lat . '")';
            DB::unprepared($query);
            return $this->index();
        } else return view('poi.create');
    }

    public function userPOI()
    {
        $query = 'select * from pois JOIN user_has_poi_ratings ON pois.poi_id = user_has_poi_ratings.poi_id where user_has_poi_ratings.user_id = "' . Auth::user()->id . '"';
        $results = DB::select($query);
        return $this->index($results, 'Von ' . Auth::user()->name . ' bewertet');
    }

    public function categoryIndex()
    {
        $query = 'select * from poi_categories';
        $categories = DB::select($query);

        $output = array();
        foreach ($categories as $category) {
            $output[] = $category->cat_name;
        }

        return view('poi.category')->with('categories', $output);
    }

    public function ratePOI()
    {
        $query = 'select * from pois JOIN poi_has_categories ON pois.poi_id = poi_has_categories.poi_id JOIN poi_categories ON poi_has_categories.cat_id = poi_categories.cat_id  where poi_categories.cat_name = "' . $category . '"';
        $results = DB::select($query);
        return redirect();
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

            /*
        $lookup = '';
        if ($output) {
            $results =  $output;
        }
        else {
            $query = 'select distinct (poi_id) from pois';
            $results = DB::select($query);
        }

        foreach ($results as $result) {
            $lookup .= $result->poi_id . ', ';
        }
        $lookup = substr($lookup, 0, -2);

        $query = 'select * from pois where poi_id IN (' . $lookup . ')';
        $results = DB::select($query);
            */

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

            /* alternative way of looking this up:
             *  $query = 'SELECT poi_id, ROUND((acos(cos(radians(' . $latitude . '))* cos(radians( lat )) * cos(radians( ' . $longitude . ') - radians( pois.long )) + sin(radians( ' . $latitude . ')) * sin(radians( lat )))) * 6371, 1) AS distance FROM pois WHERE poi_id IN (' . $lookup . ') HAVING distance <= ' . $request->distance . ';';
             * problem: select *, ROUND() FROM does not work, hence all db columns would have to be added manually which would make the code less adaptable
             */
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
        $query = 'select COUNT(*) AS total from user_has_poi_ratings where poi_id = ' . $poi_id;
        $divisor = DB::select($query);
        $divisor = $divisor[0]->total;
        $query = ('select SUM(user_has_poi_ratings.score)/' . $divisor . ' AS rating from pois join user_has_poi_ratings on pois.poi_id = user_has_poi_ratings.poi_id where user_has_poi_ratings.poi_id = ' . $poi_id);
        $results = DB::select($query);
        return $results[0]->rating;
    }

    private function getshortPOI($poi_id): array
    {
        $query = 'select COUNT(*) AS number from user_has_poi_ratings where poi_id = ' . $poi_id;
        $divisor = DB::select($query);
        $divisor = $divisor[0]->number;
        $query = 'select pois.poi_name, pois.description, pois.photo, SUM(user_has_poi_ratings.score)/' . $divisor . ' AS rating from pois LEFT JOIN user_has_poi_ratings ON pois.poi_id = user_has_poi_ratings.poi_id WHERE pois.poi_id = ' . $poi_id;
        return DB::select($query);
    }

    private function getIp()
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

}
