@extends('layouts.error')

@section('title', '403 Forbidden')

@section('content')
    <x-errors.page
        code="403"
        title="Access Forbidden"
        message="You don't have permission to access this page."
    />
@endsection
