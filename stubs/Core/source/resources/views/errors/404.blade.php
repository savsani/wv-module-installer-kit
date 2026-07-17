@extends('layouts.error')

@section('title', '404 Not Found')

@section('content')
    <x-errors.page
        code="404"
        title="Page Not Found"
        message="Sorry, the page you're looking for doesn't exist or has been moved."
    />
@endsection
