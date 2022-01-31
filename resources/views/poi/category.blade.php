@extends('adminlte::page')

@section('content_header')
    <h2 class="h4 font-weight-bold">Filtere nach Kategorien</h2>
@stop

@section('content')
<div>
    <form method="POST" action="/category">
        @csrf
        @foreach ($categories as $category)
            <input type="checkbox" name="categories[]" value="{{$category}}"> {{$category}} &nbsp;&nbsp;&nbsp;&nbsp;
        @endforeach
        <br><br>

        <div class="container pt-4">
            <div class="row">
                <div class="col-6">
                    <div class="input-group">
                        <select class="custom-select" name="rating" id="rating_select">
                            <option selected>Choose...</option>
                            <option value="1">1 Stern</option>
                            <option value="2">2 Sterne</option>
                            <option value="3">3 Sterne</option>
                            <option value="4">4 Sterne</option>
                            <option value="5">5 Sterne</option>
                        </select>
                        <div class="input-group-append">
                            <button class="edit btn btn-dark text-uppercase" type="button">Filtern</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br><br>
        <div class="container pt-4">
            <div class="row">
                <div class="col-6">
                    <div class="input-group">
                        <select class="custom-select" name="distance" id="distance_select">
                            <option selected>Choose...</option>
                            <option value="1">1 km</option>
                            <option value="2">2 km</option>
                            <option value="5">5 km</option>
                            <option value="10">10 km</option>
                            <option value="25">25 km</option>
                            <option value="50">50 km</option>
                        </select>
                        <div class="input-group-append">
                            <button class="edit btn btn-dark text-uppercase" type="button">Filtern</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br><br>
        <div class="d-flex align-items-baseline" >
            <div class="control">
                <button class="edit btn btn-dark text-uppercase" type="submit" >Suchen</button>
                &emsp;
            </div>
        </div>
    </form>
    </div>

@stop

