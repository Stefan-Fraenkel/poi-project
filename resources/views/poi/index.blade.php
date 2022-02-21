@extends('adminlte::page')
@section('content_header')
    <h2 class="h4 font-weight-bold" id="demo">Übersicht POIs: {{$category}}</h2>
@stop

@section('content')
    <div class="container">
        <div class="row align-items-start">
            @foreach($pois as $poi)

                <div class="col-sm-6 col-md-4 col-xl-3">
                    <div class="card">
                        <img src="{{$poi->photo}}"
                             class="card-img-top" alt="fallback bild">
                        <div class="card-body">
                            <h5 class="card-title">{{$poi->poi_name}}</h5>
                            <p class="card-text">
                                Entfernung: {{$poi->distance}} km
                            </p>
                            <p class="poi-rating-text"><i class="rating-icon fas fa-star"></i>{{$poi->rating}}
                                Sterne-Bewertung</p>
                            <div class="card-footer">
                                <a href="{{ url('poi/show/' . $poi->poi_id) }}" class="btn btn-outline-dark poi-more-btn">Mehr erfahren</a>
                                <a href="{{ url('poi/rate/' . $poi->poi_id) }}" class="btn btn-outline-dark poi-more-btn">Jetzt Bewerten</a>
                            </div>
                        </div>
                    </div>
                </div>


            @endforeach
        </div>
    </div>


    @push('css')

        <style>

            @media (max-width: 576px){
                .card{
                    height: 450px !important;
                }
                .card .card-img-top{
                    max-height: 232px;
                }
            }
            .card {
                height: 450px;

                margin-top: 25px;
            }
            .card .card-img-top{
                height: 192px;
            }

            .card-footer {
                background-color: transparent;
                padding-left: 0;
                margin-top: 16px;
            }
            .rating-icon {
                color: gold;
                padding-right: 5px;
            }

            .poi-more-btn {
                margin-top: 5px;
                margin-right: 5px;
            }

            .poi-rating-text {
                font-weight: bold;
                margin-bottom: 0;
                font-size: 18px;
            }
        </style>

    @endpush
@stop

