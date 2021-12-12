@extends('adminlte::page')

@section('content_header')
    <h2 class="h4 font-weight-bold">Neue Rolle anlegen</h2>
@stop

@section('content')
    <div style="padding-left: 1%; padding-right: 1%">
        <div class="card">
    <form method="POST" action="/admin/role/create">
        @csrf
        <div class="field"style="padding-left: 1%; padding-top: 1%">
            <div class="w-md-75" style="padding-left: 1%; padding-right: 2%">
            <label class="label" for="name">Name</label>
                <input class="form-control" type="text" name="role" id="role" required>
        </div>
        <br>
        <div class="w-md-75" style="padding-left: 1%; padding-right: 2%">
            <label class="label" for="roles">Bestehende Rollen</label>
            <br>
            @foreach ($roles as $role)
                {{$role->name}} &nbsp;&nbsp;&nbsp;&nbsp;
            @endforeach
        </div>

            <br>
            <div class="w-md-75" style="padding-left: 1%; padding-right: 2%">
                <label class="label" for="permissions"> Welche Rechte soll die Rolle haben?</label>
                <br>
                @foreach ($permissions as $permission)
                    <input type="checkbox" name="permissions[]" value="{{$permission->name}}"> {{$permission->name}} &nbsp;&nbsp;&nbsp;&nbsp;
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
