@php
use \Illuminate\Support\Facades\Route;
@endphp

@section('header')
    <ul class="flex items-center justify-center gap-8 mx-auto p-2">
        <li>
            <a wire:navigate href="/" class="flex flex-col items-center{{ Route::is('location') ? ' text-lime-600' : ' opacity-70' }}" title="Location">
                <svg viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                </svg>
                <span class="text-xs">Location</span>
            </a>
        </li>
        <li>
            <a wire:navigate href="/history" class="flex flex-col items-center{{ Route::is('history') ? ' text-lime-600' : ' opacity-70' }}" title="History">
                <svg viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                </svg>
                <span class="text-xs">History</span>
            </a>
        </li>
        <li>
            <a wire:navigate href="/settings" class="flex flex-col items-center{{ Route::is('settings') ? ' text-lime-600' : ' opacity-70' }}" title="Settings">
                <svg viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                </svg>
                <span class="text-xs">Settings</span>
            </a>
        </li>
    </ul>
@endsection
