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
        <div class="d-flex align-items-baseline" >
            <div class="control">
                <button class="edit btn btn-dark text-uppercase" type="submit" >Suchen</button>
                &emsp;
            </div>
        </div>
    </form>
    </div>

@stop

