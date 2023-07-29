@extends('layouts.app')

@section('header')
    @include('header')
@endsection

@section('content')
    <livewire:show-history />
@endsection
