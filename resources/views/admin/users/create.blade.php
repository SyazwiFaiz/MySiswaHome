@extends('layouts.admin')
@section('title', 'Tambah pengguna')
@section('heading', 'Tambah pengguna')
@section('content')
    <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.users._form')
    </form>
@endsection
