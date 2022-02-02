@extends('adminlte::page')
@section('content_header')
    <h2 class="h4 font-weight-bold" id="demo">Übersicht POIs: {{$category}}</h2>
@stop

@section('content')
    <div class="container">
        <div class="row align-items-start">
            @foreach($pois as $poi)

                <div class="col-sm-12 col-md-6 col-xl-4">
                    <div class="card">
                        <img src="{{$poi->photo}}" style="max-height: 192px;"
                             class="card-img-top" alt="fallback bild">
                        <div class="card-body">
                            <h5 class="card-title">{{$poi->poi_name}}</h5>
                            <p class="card-text">{{$poi->description}}
                                <br><br>
                                Entfernung: {{$poi->distance}} km
                            </p>
                            <p class="poi-rating-text"><i class="rating-icon fas fa-star"></i>{{$poi->rating}}
                                Sterne-Bewertung</p>
                            <div class="card-footer">
                                <a href="#" class="btn btn-outline-dark poi-more-btn">Mehr erfahren</a>
                            </div>
                        </div>
                    </div>
                </div>


            @endforeach
        </div>
    </div>


    @push('css')

        <style>


            .card {
                height: 500px;
                width: 18rem;
                margin-top: 25px;
            }

            .card-footer {
                background-color: white;
                padding-left: 0;
                margin-top: 18px;
            }

            .ratings {
                margin-top: 30px;
                text-align: center;
                padding: 10px 10px 0 10px;

            }

            .rating-icon {
                color: gold;
                padding-right: 5px;
            }

            .poi-more-btn {
                margin-top: 5px;
            }

            .poi-rating-text {
                font-weight: bold;
                margin-bottom: 0;
                font-size: 18px;
            }

            @media (max-width: 576px){
                .card{
                    height: 1000px;
                    width: 10px;
                }
            }
            @media (max-width: 992px){
                .card {
                    height: 500px;
                    width: 100%;
                    margin-top: 25px;
                }
            }

        </style>

    @endpush
@stop

