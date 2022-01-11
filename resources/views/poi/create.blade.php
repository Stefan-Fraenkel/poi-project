@extends('adminlte::page')

@section('content_header')
    <h2 class="h4 font-weight-bold">Neuen POI Anlegen</h2>
@stop

@section('content')
<div>
    <form method="POST" action="/poi/create">
        @csrf
        <label for="poiName" class="form-label">POI Name</label>
        <input name="name" type="text" id="poiName" class="form-control">
        <label for="poiStrasse" class="from-label">Strasse</label>
        <input name="street" type="text" id="poiStrasse" class="form-control">
        <label for="poiPlz" class="form-label">PLZ</label>
        <input name="plz" type="number" id="poiPlz" class="form-control">
        <label for="poiOrt" class="from-label">Ort</label>
        <input name="city" type="text" id="poiOrt" class="form-control">
        <label for="poiBeschreibung" class="form-label">Beschreibung</label>
        <textarea name="description" id="poiBeschreibung" class="form-control"></textarea>
        <div class="d-flex align-items-baseline" >
            <div class="control">
                <button class="edit btn btn-dark text-uppercase" type="submit" >Speichern</button>
                &emsp;
            </div>
        </div>
    </form>
    </div>

@stop

