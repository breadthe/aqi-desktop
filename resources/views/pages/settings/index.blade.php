@extends('layouts.app')

@section('header')
    @include('header')
@endsection

@section('content')
    <div class="w-full flex flex-col mx-auto py-2">
        <div class="w-full flex flex-col sm:flex-row gap-4 items-start px-8">
            <div class="flex flex-col sm:w-1/4 sm:text-right">
                <strong>API key</strong>
                <span class="text-xs">
                    Request an API key by signing up on the <a onclick="shell.openExternal('https://docs.airnowapi.org/account/request/')" href="javascript:;" title="AirNow dev portal" class="underline">AirNow dev portal</a>
                </span>
            </div>
            <div class="w-full sm:w-3/4">
                <livewire:api-key />
            </div>
        </div>
    </div>
@endsection
