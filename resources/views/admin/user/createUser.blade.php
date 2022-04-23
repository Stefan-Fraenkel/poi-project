@extends('adminlte::page')

@section('content_header')
    <h2 class="h4 font-weight-bold">Neuen Nutzer anlegen</h2>
@stop

@section('content')
    <div style="padding-left: 1%; padding-right: 1%">
        <div class="card">
    <form method="POST" action="/admin/user/create">
        @csrf
        <div class="field"style="padding-left: 1%; padding-top: 1%">
            <div class="w-md-75" style="padding-left: 1%; padding-right: 2%">
            <label class="label" for="name">Name</label>
                <input class="form-control" type="text" name="name" id="name" required>
        </div>
        <br>
            <div class="w-md-75" style="padding-left: 1%; padding-right: 2%">
            <label class="label" for="email">E-Mail</label>
                <input class="form-control" type="text" name="email" id="email" required>
        </div>
        <br>
            <div class="w-md-75" style="padding-left: 1%; padding-right: 2%">
            <label class="label" for="password">Passwort</label>
                <input class="form-control" type="text" name="password" id="password">
        </div>
        <br>
            <div class="w-md-75" style="padding-left: 1%; padding-right: 2%">
            <label class="label" for="roles">Rollen</label>
                <br>
                @foreach ($roles as $role)
                    <input type="checkbox" name="roles[]" value="{{$role->name}}"> {{$role->name}} &nbsp;&nbsp;&nbsp;&nbsp;
                @endforeach
        </div>
        <br>
        <div class="w-md-75" style="padding-left: 1%; padding-right: 2%">
            <label class="label" for="permissions">Berechtigungen</label>
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
