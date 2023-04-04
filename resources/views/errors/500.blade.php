@extends('master-pages.body')
@section('content')
    <div class="container">
        @isset($message)
        <div class="alert alert-danger" role="alert">
            {{$message}}
        </div>
        @endisset
            
    </div>
@endsection