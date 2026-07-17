@extends('layouts.error')

@section('title', '503 Service Unavailable')

@section('content')
    <x-errors.page
        code="503"
        title="Service Unavailable"
        message="We're currently performing scheduled maintenance. Please check back shortly."
        :show-action="false"
    />
@endsection
