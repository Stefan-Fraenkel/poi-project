@extends('adminlte::page')

@section('content_header')
    <h2 class="h4 font-weight-bold">Neue Berechtigung anlegen</h2>
@stop

@section('content')
    <div style="padding-left: 1%; padding-right: 1%">
        <div class="card">
    <form method="POST" action="/admin/perm/create">
        @csrf
        <div class="field"style="padding-left: 1%; padding-top: 1%">
            <div class="w-md-75" style="padding-left: 1%; padding-right: 2%">
            <label class="label" for="name">Name</label>
                <input class="form-control" type="text" name="permission" id="permission" required>
        </div>
        <br>
        <div class="w-md-75" style="padding-left: 1%; padding-right: 2%">
            <label class="label" for="permissions">Bestehende Berechtigungen</label>
            <br>
            @foreach ($permissions as $permission)
                {{$permission->name}} &nbsp;&nbsp;&nbsp;&nbsp;
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
