@extends('adminlte::page')

@section('content_header')
    <h2 class="h4 font-weight-bold">Übersicht POIs: {{$category}}</h2>
@stop

@section('content')
    <div class="container">
        <div class="row align-items-start">
            <div class="col">
                @foreach($pois as $poi)
                    <div class="card" style="width: 18rem;">
                        <img src="{{$poi->photo}}"
                             class="card-img-top" alt="fallback bild">
                        <div class="card-body">
                            <h5 class="card-title">{{$poi->poi_name}}</h5>
                            <p class="card-text">{{$poi->description}}</p>
                            <p class="poi-rating-text"><i class="rating-icon fas fa-star"></i>{{$poi->rating}}
                                Sterne-Bewertung</p>
                            <div class="card-footer">
                                <a href="#" class="btn btn-outline-dark poi-more-btn">Mehr erfahren</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
@stop
