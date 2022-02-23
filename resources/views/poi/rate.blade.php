@extends('adminlte::page')

@section('content_header')
@stop

@section('content')

    <div class="container pt-5">
        <div class="card">
            <div class="card-header ratingCardHeader">
                Neue Bewertung für "{{$poi->poi_name}}" schreiben:
            </div>
            <div class="card-body">
                <form method="POST" action="/poi/rate">
                    @csrf
                    <label for="poiComment" class="form-label">Meine Bewertung:</label>
                    <textarea name="comment" id="poiComment" class="form-control neuRatingInput" required></textarea>
                    <br>
                    <div class="input-group scoreInput">
                        <select class="custom-select" name="score" id="rating_select" required>
                            <option value="" selected>Sternebewertung wählen</option>
                            <option value="1">1 Stern</option>
                            <option value="2">2 Sterne</option>
                            <option value="3">3 Sterne</option>
                            <option value="4">4 Sterne</option>
                            <option value="5">5 Sterne</option>
                        </select>
                    </div>
                    <input type="hidden" name="poi_id" value="{{$poi->poi_id}}">


                    <div class="d-flex align-items-baseline">
                        <div class="control pt-4">
                            <button class="edit btn btn-dark text-uppercase" type="submit">Speichern</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


    </div>


    @push('css')

        <style>

            @media (max-width: 768px){
                .scoreInput{
                    width: 100% !important;
                }
                .neuRatingInput{
                    width: 100% !important;
                }
            }
            .ratingCardHeader{
                color: white;
                background: #ad9593;
            }
            .scoreInput{
                width:60%
            }
            .neuRatingInput{
                height: 100px;
                width: 60%;
            }
        </style>

    @endpush



@stop
