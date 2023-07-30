@php
use \Illuminate\Support\Facades\Route;
@endphp

@section('header')
    <ul class="flex gap-2">
        <li><a href="/" class="{{ Route::is('index') ? 'text-lime-600 font-bold' : '' }}">Home</a></li>
        <li><a href="/history" class="{{ Route::is('history') ? 'text-lime-600 font-bold' : '' }}">History</a></li>
        <li><a href="/settings" class="{{ Route::is('settings') ? 'text-lime-600 font-bold' : '' }}">Settings</a></li>
    </ul>
@endsection
