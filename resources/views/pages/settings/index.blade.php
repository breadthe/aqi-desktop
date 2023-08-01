@extends('layouts.app')

@section('header')
    @include('header')
@endsection

@section('content')
    <div class="w-full flex flex-col mx-auto py-2">
        <div class="w-full flex flex-col sm:flex-row gap-4 items-start px-8">
            <div class="flex flex-col sm:w-1/4 sm:text-right">
                <strong>API key</strong>
            </div>
            <div class="w-full sm:w-3/4">
                <livewire:api-key />
            </div>
        </div>
    </div>
@endsection
