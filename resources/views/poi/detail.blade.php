@extends('adminlte::page')

@section('content_header')
@stop

@section('content')
    <!--###################################Kopfberiech mit Bild und Adressse ########################-->
    <div class="container detailContainer">
        <div class="row align-content-center">
            <div class="col-sm-12 col-md-6 pb-2">
                <img src="{{$poi->photo}}" class="img-fluid" alt="POI-Picture">
            </div>
            <div class="col-sm-12 col-md-6 mt-4 text-left adressBlock">
                <h3>{{$poi->poi_name}}</h3>
                <span class="pr-3"><i class="fas fa-map-marker-alt pr-2"></i>{{$poi->distance}} km Entfernung</span>
                <span><i class="fas fa-star pr-2 rating-star"></i>{{$poi->score}} Sternebewertung</span>
                <hr>
                <p style="margin-bottom:0;"><b>Adresse</b></p>
                <span>{{$poi->street}} <br> {{$poi->zipcode}} {{$poi->city}}</span>
            </div>
            <!--################################Kategorien#########################################-->
            <hr class="trennerCol">
            <div class="col-sm-0 col-md-2"></div>
            <div class="col-sm-12 col-md-8 text-center">
                @foreach($poi->cat_names as $category)
                    <button class="btn btn-default disabled categoryChip">{{$category}}</button>
                @endforeach
            </div>
            <div class="col-sm-0 col-md-2"></div>
            <!--################################Beschreibung#########################################-->

            <hr class="trennerCol">
            <div class="col-sm-0 col-md-2"></div>
            <div class="col-sm-12 col-md-8 pr-5">
                <p><b>Beschreibung</b></p>
                <p>{{$poi->description}}</p>
            </div>
            <div class="col-sm-0 col-md-2"></div>
            <!--################################Webseite#########################################-->

            <hr class="trennerCol">
            <div class="col-sm-0 col-md-2"></div>
            <div class="col-sm-12 col-md-8 text-center">
                <a class="btn btn-social btn-website" target="_blank" href="{{$poi->website}}">
                    <i class="fas fa-globe"></i> Zur Webseite
                </a>
            </div>
            <div class="col-sm-0 col-md-2"></div>
            <!--################################Öffnungszeiten#########################################-->
            <hr class="trennerCol">
            <div class="col-sm-0 col-md-2"></div>
            <div class="col-sm-12 col-md-8">
                <div class="card-header open-header">
                    <h3 class="card-title">Öffnungszeiten</h3>
                </div>
                <div class="card-body open-body">
                    {{$poi->open}}
                </div>
            </div>
            <div class="col-sm-0 col-md-2"></div>
            <!--################################Bewertung#########################################-->
            <hr class="trennerCol">
            <div class="col-sm-0 col-md-2"></div>
            <div class="col-sm-12 col-md-8">
                <p><b>Bewertungen:</b></p>
                <div class="card-footer card-comments">
                    @foreach($poi->users as $user)
                        <div class="card-comment">
                            <img class="img-circle img-sm" style="border-radius: 50%;" src="{{$user["photo"]}}"
                                 alt="User Image">
                            <div class="comment-text">
                            <span class="username">
                                {{$user["name"]}}
                                <span class="text-muted float-right"><span
                                        class="pr-1 ratings">{{$user["rating"]}}</span><i
                                        class="fas fa-star pr-2 rating-star"></i></span>
                            </span>{{$user["comment"]}}
                            </div>

                        </div>
                    @endforeach
                </div>
                <a href="{{ url('poi/rate/' . $poi->poi_id) }}" class="btn btn-outline-dark mt-3 poi-more-btn float-right"><i
                        class="fas fa-plus addIcon"></i> Neue Bewertung schreiben</a>
            </div>
            <div class="col-sm-0 col-md-2"></div>
        </div>
        <a href="/poi" class="btn btn-dark mt-5 poi-more-btn"></i> Zurück zur Übersicht</a>

    </div>

    @push('css')

        <style>
            @media (max-width: 576px) {

                .categoryChip {
                    width: 30% !important;
                }
                .trennerCol{
                    margin: 5% 2% !important;
                }
            }

            @media (max-width: 768px) {
                .adressBlock {
                    padding-top: 0px !important;
                }
            }

            .adressBlock {
                padding-top: 5%;

            }

            .categoryChip {
                width: 20%;
                padding: 5px;
            }

            .btn.disabled {
                cursor: auto;
            }

            .detailContainer {
                background-color: white;
                box-shadow: 0 0 1em rgba(0, 0, 0, 0.2);
                padding: 0px 35px 50px 35px;

            }

            .img-circle {
                border-radius: 50%;
            }

            .open-header {
                padding: 12px 20px;
                background: #ad9593;
                color: white;
            }

            .open-body {
                border: 1px solid #ad9593;
            }

            .btn-website {
                background-color: #ad9593;
                width: 150px;
                color: white;
            }

            .btn-website:hover {
                background: white;
                border: 1px solid #ad9593;
                color: #ad9593;
            }

            .trennerCol {
                width: 100%;
                margin: 3% 2%;
            }

            .rating-star {
                color: gold;
                font-size: 15px;
            }

            .ratings {
                font-size: 18px;
            }

            .addIcon {
                font-size: 13px;
            }

        </style>

    @endpush



@stop

