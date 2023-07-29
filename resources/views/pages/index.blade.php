@extends('layouts.app')

@section('header')
    @include('header')
@endsection

@section('content')
    <livewire:fetch-observation />

    <livewire:show-observation />
@endsection
