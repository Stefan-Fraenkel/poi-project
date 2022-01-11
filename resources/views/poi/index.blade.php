@extends('adminlte::page')

@section('content_header')
    <h2 class="h4 font-weight-bold">Übersicht aller POIs</h2>
@stop

@section('content')
    <div class="container">
        <div class="row align-items-start">
            <div class="col">
                @foreach($pois as $poi)
                    <div class="card" style="width: 18rem;">
                        <img src="https://cdn.pixabay.com/photo/2021/11/25/14/37/detail-6823782_960_720.jpg"
                             class="card-img-top" alt="fallback bild">
                        <div class="card-body">
                            <h5 class="card-title">{{$poi->name}}</h5>
                            <p class="card-text">{{$poi->beschreibung}}</p>
                            <p class="poi-rating-text"><i class="rating-icon fas fa-star"></i>{{$poi->durchschnittsbewertung}}
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
