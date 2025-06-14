@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Selamat Datang, ' . auth()->user()->name)

 
@section('content')
<div class="bg-white rounded-lg shadow-sm p-5 mb-6">
            <h1 class="text-2xl font-semibold text-gray-800 mb-1">@yield('page_title', 'Selamat Datang')</h1>
        </div>
    <div class="bg-white rounded-lg shadow-sm p-6 text-center">
        
        <p class="text-lg text-gray-700">
            Anda berhasil masuk ke dashboard.
        </p>
    </div>
@endsection
