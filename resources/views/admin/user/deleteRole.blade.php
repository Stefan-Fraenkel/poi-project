@extends('adminlte::page')

@section('content_header')
    <h2 class="h4 font-weight-bold">Rolle löschen</h2>
@stop

@section('content')
    <div style="padding-left: 1%; padding-right: 1%">
        <div class="card">
    <form method="POST" action="/admin/role/delete">
        @csrf
        <div class="field"style="padding-left: 1%; padding-top: 1%">
            <div class="w-md-75" style="padding-left: 1%; padding-right: 2%">
               <label class="label" for="roles">Welche Rollen sollen gelöscht werden?</label>
                <br>
                @foreach ($roles as $role)
                    <input type="checkbox" name="roles[]" value="{{$role->name}}"> {{$role->name}} &nbsp;&nbsp;&nbsp;&nbsp;

                @endforeach
        </div>
            <br>
        </div>
        <div class="card-footer d-flex justify-content-end">
            <div class="d-flex align-items-baseline" >
                <div class="control">
                    <button class="edit btn btn-dark text-uppercase" type="submit" >Speichern</button>
                    &emsp;
                </div>

            </div>

@stop
