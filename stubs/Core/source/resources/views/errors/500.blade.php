@extends('layouts.error')

@section('title', '500 Server Error')

@section('content')
    <x-errors.page
        code="500"
        title="Server Error"
        message="Something went wrong on our end. Please try again later."
        :show-action="false"
    />
@endsection
