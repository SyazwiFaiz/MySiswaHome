@extends('layouts.admin')
@section('title', 'Edit rumah')
@section('heading', 'Edit rumah')
@section('content')
    <form method="POST" action="{{ route('admin.houses.update', $house) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.houses._form')
    </form>
@endsection
