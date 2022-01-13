<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class POIController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function initialSetup () {

        /* new:
        DB::unprepared('CREATE TABLE poiTable (poiID int primary key auto_increment, name varchar(100), strasse varchar(50), plz int, ort varchar(50), beschreibung varchar(200), oeffnungszeiten varchar (100), website varchar(100), foto varchar(2048))');
        DB::unprepared('CREATE TABLE kategorienTable (kategorienID int primary key auto_increment, kategorienName varchar(100))');
        DB::unprepared('CREATE TABLE poiKategorienTable (poiID int, kategorienID int, foreign key (poiID) references poiTable(poiID), foreign key (kategorienID) references kategorienTable(kategorienID))');
        DB::unprepared('CREATE TABLE nutzerTable (nutzerID int primary key auto_increment, benutzername varchar(10), passwort varchar(10), avatar varchar(2048), interneNotiz varchar (400),  admin boolean)');
        DB::unprepared('CREATE TABLE nutzerPoiTable (nutzerID int, poiID int, foreign key (nutzerID) references nutzerTable(nutzerID), foreign key (poiID) references poiTable(poiID), bewertung float, referenz varchar(100))');

        DB::unprepared('INSERT INTO poiTable (name, strasse, plz, ort, beschreibung, oeffnungszeiten, website, foto) VALUES ("Pizzaria Gargano", "Badeweg 3", 10997, "Berlin", "Nette Pizzaria, mit kleiner Sonnenterrasse...", "Täglich 12:00 Uhr bis 21:00 Uhr", "https://pizza-gargano.de/", "https://images.pexels.com/photos/905847/pexels-photo-905847.jpeg?auto=compress&cs=tinysrgb&dpr=3&h=750&w=1260"), ("Park Theater", "Seeweg 5", 10997, "Berlin", "Nachtclub mit wechselnden DJs und Happy-Hour von 23:00 Uhr - 24:00 Uhr", "Freitag + Samstag von 22:00 Uhr bis 05:00 Uhr", "https://parktheater.de/", "https://images.pexels.com/photos/2114365/pexels-photo-2114365.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260"), ("Naruto Sush", "Wengen 4", 10997, "Berlin", "Sushi Spezialitäten aus hochqualitativem Fisch.", "Dienstag bis Sonntag von 11:00 Uhr bis 22:00 Uhr", "https://naruto-sushi.de/", "https://images.pexels.com/photos/2098085/pexels-photo-2098085.jpeg?auto=compress&cs=tinysrgb&dpr=3&h=750&w=1260"), ("Fitness Park Dream Fit", "Sportstrasse 21", 10997, "Berlin", "Moderner Fitnesspark mit vielen Geräten und professionellem Team.", "Täglich von 06:00 Uhr bis 23:00 Uhr", "https://dream-fit.de/", "https://images.pexels.com/photos/1954524/pexels-photo-1954524.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260"), ("Kinder Kletterpark", "Rattenweg 56", 10997, "Berlin", "Indoor Klettergarten für Kinder mit Selbstbedienungs Restaurant.", "Donnerstag bis Sonntag von 10:00 Uhr bis 18:00 Uhr", "https://kletterpark-hoch-hinaus.de/", "https://images.pexels.com/photos/5383729/pexels-photo-5383729.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260")');
        DB::unprepared('INSERT INTO kategorienTable (kategorienName) VALUES ("Restaurant"), ("Sport"), ("Nachtleben"), ("Einkaufen"), ("Erleben")');
        DB::unprepared('INSERT INTO nutzerTable (benutzername, passwort, admin) VALUES ("stefan", "asf45s8e", true), ("theresa", "saohf35", true), ("testMann", "aaf435", false), ("bernd", "agd98d#", false), ("susi", "aojf354", false)');
        DB::unprepared('INSERT INTO nutzerPoiTable (nutzerID, poiID, bewertung, referenz) VALUES (1,4,4.5, "tolle aussicht"), (1,3,6.0, "gutes Essen"), (2,4,1.5, "schlechter Service"), (3,2,10.0, "Alles top, gerne Wieder"), (4,1,8.5, "mega Stimmung und nettes Personal"), (5,5,5.5, "war oke"), (5,3,7.0, "geeignet für Familien")');

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




        return view('dashboard');

    }

    public function searchPOIs(Request $request) {
        $output = array();
        if($request->categories) {
             $output = $this->arrayMergeUnique($output, $this->categoryFilter($request));
        }
        if($request->ratings) {
            $output = $this->arrayMergeUnique($output, $this->ratingsFilter($request));
        }
        if($request->distance) {
            $output = $this->arrayMergeUnique($output, $this->distanceFilter($request));
        }
        if($request->categories || $request->ratings || $request->distance) {
            return $this->index($output, 'Suche');
        }
        else return $this->categoryIndex();
    }


    private function categoryFilter(Request $request){
            $resultsCategory = array();
            foreach ($request->categories as $category) {
                $query = 'select * from poitable JOIN poikategorientable ON poitable.poiID = poikategorientable.poiID JOIN kategorientable ON poikategorientable.kategorienID = kategorientable.kategorienID  where kategorientable.kategorienName = "' . $category . '"';
                $results = DB::select($query);

                /*
                $query = 'select * from kategorientable where kategorienName = "' . $category . '"';
                $categoryName = DB::select($query);
                $categoryName = $categoryName[0] -> kategorienName;
                */

                $resultsCategory = $this->arrayMergeUnique($resultsCategory, $results);

            };

            return $resultsCategory;
        }

    public function ratingsFilter(Request $request){
        $resultsRating = null;
        return $resultsRating;
    }
    public function distanceFilter(Request $request){
        $resultsDistance = null;
        return $resultsDistance;
    }

    public function categoryIndex(){
        $query = 'select * from kategorientable';
        $categories = DB::select($query);

        $output = array();
        foreach ($categories as $category) {
            $output[] = $category->kategorienName;
        }

        return view('poi.category')->with('categories', $output);
    }


    private function arrayMergeUnique($base_array, $add_array): array
    {
        if ($base_array){
            foreach ($add_array as $key => $value){
                foreach ($base_array as $base_value){
                    if ($base_value->poiID == $value->poiID){
                    unset($add_array[$key]);
                    }
                }
            }
                  return array_merge_recursive($base_array, $add_array);
        }
        else return $add_array;
    }

    public function index($results=null, $category='Alle'){
        if ($results == null) {
            $query = 'select * from poitable';
            $results = DB::select($query);
        }
        $outputs = array();
        foreach ($results as $result) {
            $entry = $this->getshortPOI($result -> poiID);
            array_push($outputs, $entry[0]);
        }
        return view('poi.index') -> with('pois', $outputs) -> with('category', $category);
    }

    public function create(Request $request){
        if($request->isMethod('post')) {
          //  dd($request);
            $query = 'INSERT INTO poiTable (name, strasse, plz, ort, beschreibung, oeffnungszeiten, website, foto) VALUES ("' . $request->name . '", "' . $request->street . '", "' . $request->plz . '", "' . $request->city . '", "' . $request->description . '", "' . $request->openingHours . '", "'. $request->website . '", "' . $request->photo .'")';
            DB::unprepared($query);
            return $this->index();
        }
        else return view('poi.create');
    }

    public function getshortPOI($poiid): array
    {
        $query = 'select COUNT(*) AS number from nutzerpoitable where poiid = ' . $poiid;
        $divisor = DB::select($query);
        $divisor = $divisor[0] -> number;
        $query = 'select poitable.name, poitable.beschreibung, poitable.foto, SUM(nutzerpoitable.bewertung)/' . $divisor . ' AS durchschnittsbewertung from poitable LEFT JOIN nutzerpoitable ON poitable.poiID = nutzerpoitable.poiID WHERE poitable.poiid = '  . $poiid;
        return DB::select($query);
    }

}
