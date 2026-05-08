@extends('layouts.app')

@section('title', 'Nouvelle pharmacie - Gestion Pharmacie')

@section('content')
    <form action="{{ route('pharmacies.store') }}" method="POST">
        @csrf
        @include('pharmacies.form', ['buttonText' => 'Créer'])
    </form>
@endsection
