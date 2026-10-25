@extends('layouts.admin')
@section('title', 'Profil')
@section('heading', 'Profil saya')

@section('content')
    @if ($errors->any() && ! $errors->hasBag('updatePassword'))
        <div class="mb-5 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-900">{{ $errors->first() }}</div>
    @endif
    @include('account._profile-forms')
@endsection
