@extends('adminlte::page')

@section('content_header')
    <h2 class="h4 font-weight-bold">Neuen POI Anlegen</h2>
@stop

@section('content')
<div>
    <form method="POST" action="/poi/create">
        @csrf
        <div class="row align-content-start">
            <div class="col-12">
                <label for="poiName" class="form-label">POI Name</label>
                <input name="name" type="text" id="poiName" class="form-control">
            </div>
        </div>
        <div class="row align-items-start">
            @foreach ($categories as $category)

                <div class="col-sm-6 col-md-2 ml-4">
                    <input class="form-check-input checkbox" type="checkbox" name="categories[]"  value="{{$category}}">
                    <label class="form-check-label checkox-label" for="flexCheckDefault">
                        {{$category}} &nbsp;&nbsp;
                    </label>
                </div>


            @endforeach
        </div>
        <label for="poiStrasse" class="from-label">Strasse</label>
        <input name="street" type="text" id="poiStrasse" class="form-control"required>
        <label for="poiPlz" class="form-label">PLZ</label>
        <input name="zipcode" type="number" id="poiPlz" class="form-control"required>
        <label for="poiOrt" class="from-label">Ort</label>
        <input name="city" type="text" id="poiOrt" class="form-control"required>

                <div class="col-sm-12 col-md-6 pt-3">                    <label for="poiBeschreibung" class="form-label">Beschreibung</label>
                    <textarea name="description" id="poiBeschreibung" class="form-control"></textarea>
                </div>

                <div class="col-sm-12 col-md-6 pt-3">                    <label for="openingHours" class="from-label">Öffnungszeiten</label>
                    <textarea name="open" id="openingHours" class="form-control"></textarea>
                </div>
                <div class="col-sm-12 col-md-6 pt-3">                    <label for="website" class="from-label">Website (URL)</label>
                    <input name="website" type="text" id="website" class="form-control">
                </div>
                <div class="col-sm-12 col-md-6 pt-3">                    <label for="photo" class="from-label">Foto-Link</label>
                    <input name="photo" type="text" id="photo" class="form-control">
                </div>
                <div class="col-sm-12 col-md-6 pt-3"><label for="poiLong" class="form-label">Längengrad</label>
                    <input name="long" type="number" id="poiLong" class="form-control"></div>
                <div class="col-sm-12 col-md-6 pt-3"><label for="poiLat" class="form-label">Breitengrad</label>
                    <input name="lat" type="number" id="poiLat" class="form-control"></div>
            </div>
            <div class="d-flex align-items-baseline">
                <div class="control pt-4">
                    <button class="edit btn btn-dark text-uppercase" type="submit">Speichern</button>
                    &emsp;
                </div>
            </div>
        </form>
    </div>

@stop

