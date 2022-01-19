<html>
@extends('adminlte::page')
<header>

</header>
@section('content')
    <body>

    <div class="container">
        @foreach($theresa as $poi)
            <p> Bewertungen: {{$poi[0]->rating}}</p>
        @endforeach
    </div>

<div class="container pt-4">
    <div class="row">
        <div class="col-6">
            <div class="input-group">
                <select class="custom-select" id="rating_select">
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
    </body>
@stop
</html>
