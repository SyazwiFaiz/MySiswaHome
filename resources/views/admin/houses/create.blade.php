@extends('layouts.admin')
@section('title', 'Tambah rumah')
@section('heading', 'Tambah rumah')
@section('content')
    <form method="POST" action="{{ route('admin.houses.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.houses._form')
    </form>
@endsection
