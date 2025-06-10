@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Selamat Datang, ' . auth()->user()->name)
@section('page_description', 'Role Anda: ' . auth()->user()->role)

@section('content')
    <div class="bg-white rounded-lg shadow-sm p-6 text-center">
        <p class="text-lg text-gray-700">
            Anda berhasil masuk ke dashboard.
        </p>
    </div>
@endsection
