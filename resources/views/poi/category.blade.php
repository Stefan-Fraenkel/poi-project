@extends('adminlte::page')
@section('content_header')
    <h2 class="h4 font-weight-bold">Suche filtern</h2>
@stop


@section('content')
<div>
    <form method="POST" action="{{ url('/poi/search')}}">
        @csrf
        <div class="row align-items-start">
        @foreach ($categories as $category)

                <div class="col-sm-6 col-md-2 ml-4">
                    <input class="form-check-input checkbox" type="checkbox" name="categories[]"  value="{{$category}}">
                    <label class="form-check-label checkox-label" for="flexCheckDefault">
                        {{$category}} &nbsp;&nbsp;
                    </label>
                </div>


        @endforeach
        </div>
            <div class="row">
                <div class="col-sm-12 col-md-5 col-lg-4 pt-4">
                    <div class="input-group">
                        <select class="custom-select" name="rating" id="rating_select">
                            <option value="" selected>Sternebewertung wählen</option> {{-- selected value must be null for filter to function properly --}}
                            <option value="1">1 Stern</option>
                            <option value="2">2 Sterne</option>
                            <option value="3">3 Sterne</option>
                            <option value="4">4 Sterne</option>
                            <option value="5">5 Sterne</option>
                        </select>
                    </div>
                </div>

                <div class="col-sm-12 col-md-5 col-lg-4 pt-4">
                    <div class="input-group">
                        <select class="custom-select" name="distance" id="distance_select">
                            <option value="" selected>Entfernung wählen</option> {{-- selected value must be null for filter to function properly --}}
                            <option value="1">1 km</option>
                            <option value="2">2 km</option>
                            <option value="5">5 km</option>
                            <option value="10">10 km</option>
                            <option value="25">25 km</option>
                            <option value="50">50 km</option>
                        </select>
                    </div>
                </div>
            </div>
        <div class="d-flex align-items-baseline pt-4">
            <div class="control">
                <button class="edit btn btn-dark text-uppercase" type="submit">Suchen</button>
                &emsp;
            </div>
        </div>
    </form>
    </div>
@push('css')

    <style>
        .checkbox{
            height: 15px;
            width: 15px;
        }
        .checkox-label{
            font-weight:bold;
            font-size: 18px;
        }
    </style>

@endpush
@stop

