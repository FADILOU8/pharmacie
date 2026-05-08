@extends('layouts.app')

@section('title', 'Éditer une pharmacie - Gestion Pharmacie')

@section('content')
    <form action="{{ route('pharmacies.update', $pharmacy) }}" method="POST">
        @csrf
        @method('PUT')
        @include('pharmacies.form', ['buttonText' => 'Mettre à jour'])
    </form>
@endsection
