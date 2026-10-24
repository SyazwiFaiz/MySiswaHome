@extends('layouts.admin')
@section('title', 'Edit pengguna')
@section('heading', 'Edit pengguna')
@section('content')
    <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.users._form')
    </form>
@endsection
