@extends('certificate::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('certificate.name') !!}</p>
@endsection
