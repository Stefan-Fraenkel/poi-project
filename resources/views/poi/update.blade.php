@extends('adminlte::page')

@section('content_header')
    <h2 class="h4 font-weight-bold">POI aktualisieren</h2>
@stop

@section('content')
<div>
    <form method="POST" action="/poi/update">
        @csrf
        <label for="poiName" class="form-label">POI Name</label>
        <input name="poi_name" type="text" id="poiName" class="form-control" required value="{{$poi->poi_name}}">
        <label for="poiStrasse" class="from-label">Strasse</label>
        <input name="street" type="text" id="poiStrasse" class="form-control"required value="{{$poi->street}}">
        <label for="poiPlz" class="form-label">PLZ</label>
        <input name="zipcode" type="number" id="poiPlz" class="form-control"required value="{{$poi->zipcode}}">
        <label for="poiOrt" class="from-label">Ort</label>
        <input name="city" type="text" id="poiOrt" class="form-control"required value="{{$poi->city}}">

        <label for="poiBeschreibung" class="form-label">Beschreibung</label>
        <textarea name="description" id="poiBeschreibung" class="form-control" value="{{$poi->description}}"></textarea>

        <label for="openingHours" class="from-label">Öffnungszeiten</label>
        <input name="open" type="text" id="openingHours" class="form-control" value="{{$poi->open}}">

        <label for="website" class="from-label">Website</label>
        <input name="website" type="text" id="website" class="form-control" value="{{$poi->website}}">

        <label for="photo" class="from-label">Foto</label>
        <input name="photo" type="text" id="photo" class="form-control" value="{{$poi->photo}}">

        <label for="poiLong" class="form-label">Längengrad</label>
        <input name="long" type="number" id="poiLong" class="form-control"value="{{$poi->long}}">

        <label for="poiLat" class="form-label">Breitengrad</label>
        <input name="lat" type="number" id="poiLat" class="form-control" value="{{$poi->lat}}">

        <input type="hidden" name="poi_id" value="{{$poi->poi_id}}">

        <div class="d-flex align-items-baseline" >
            <div class="control">
                <button class="edit btn btn-dark text-uppercase" type="submit" >Speichern</button>
                &emsp;
            </div>
        </div>
    </form>
    </div>

@stop

