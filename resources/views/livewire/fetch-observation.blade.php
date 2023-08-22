<?php

use App\Models\Observation;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Native\Laravel\Facades\Settings;
use Illuminate\Support\Facades\DB;
use function Livewire\Volt\{state, mount, boot, rules, on};

// settings
state(['apiKey' => null]);
state(['zip' => null]);
state(['lastUpdatedAt' => null]);

state(['newObservation' => null]);
state(['fetchError' => false]);
state(['zipHistory' => collect()]);

rules(['zip' => 'required|numeric|digits:5']);

mount(function () {
    if ($this->zip) {
        $this->fetch();
    }

    $this->zipHistory = $this->getZipHistory();
});

boot(function () {
    $this->apiKey = Settings::get('api_key');
    $this->zip = Settings::get('last_zip');
    $this->lastUpdatedAt = Settings::get('last_updated_at');
});

// @todo why is zipChanged fired twice???
on(['zipChanged' => fn ($zip) => $this->changeZip($zip)]);

$fetch = function () {
    $this->fetchError = false;

    $this->validate();

    $this->newObservation = null;

    $url = "https://www.airnowapi.org/aq/observation/zipCode/current/?format=application/json&zipCode=$this->zip&distance=25&API_KEY=$this->apiKey";

    Settings::set('last_zip', $this->zip);

    // @todo try catch
    try {
        $response = Http::get($url);

        // @todo catch invalid api key

        $this->lastUpdatedAt = now();
        $this->newObservation = $response->json();

        if (is_array($this->newObservation) && empty($this->newObservation)) {
            $this->fetchError = true;

            return;
        }

        Settings::set('last_updated_at', $this->lastUpdatedAt);
        Settings::set('last_observation', $this->newObservation);
        $this->zipHistory = $this->getZipHistory();

        $this->saveObservation();

        $this->dispatch('observation-updated');
    } catch (\Exception $e) {
        Log::error($e->getMessage());
    }
};

$saveObservation = function () {
    foreach ($this->newObservation as $parameter) {
        $this->saveObservationParameter($parameter);
    }
};

$saveObservationParameter = function (array $parameter) {
    Observation::query()->updateOrCreate(
        [
            'zip_code' => $this->zip,
            'date_observed' => $parameter['DateObserved'],
            'hour_observed' => $parameter['HourObserved'],
            'parameter_name' => $parameter['ParameterName'],
        ],
        [
            'local_time_zone' => $parameter['LocalTimeZone'],
            'reporting_area' => $parameter['ReportingArea'],
            'state_code' => $parameter['StateCode'],
            'latitude' => $parameter['Latitude'],
            'longitude' => $parameter['Longitude'],
            'aqi' => $parameter['AQI'],
            'category_number' => $parameter['Category']['Number'],
            'category_name' => $parameter['Category']['Name'],
            'updated_at' => now(),
        ]
    );
};

$getZipHistory = function (): Collection {
    try {
        return DB::table('observations')
            ->selectRaw('DISTINCT zip_code, reporting_area')
            ->orderBy('zip_code')
            ->get();
    } catch (\Exception $e) {
        Log::error($e->getMessage());
    }

    return collect();
};

$changeZip = function (string $zip): void {
    $this->zip = $zip;
    Settings::set('last_zip', $this->zip);
};
?>

<div class="flex flex-col gap-4 py-2 px-8" @class(['h-full' => isset($apiKey)])>
    @empty($apiKey)
        <div class="w-full text-center bg-lime-900 rounded my-auto p-4">
            <span class="text-lime-500">No AirNow API key, register it in <a href="{{ route('settings') }}"
                                                                             class="underline">Settings</a></span>
        </div>
    @else
        <form wire:submit.prevent="fetch" class="w-full flex flex-col items-center gap-2">
            <div class="w-full flex items-center justify-center gap-2 relative" x-data="{showHistory: false}">
                <input type="text" id="zip" wire:model="zip" maxlength="5" placeholder="Zip code"
                       @focus="showHistory = true">
                <button type="submit">
                    <svg viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6">
                        <path fill-rule="evenodd"
                              d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                              clip-rule="evenodd"/>
                    </svg>
                </button>

                @if($zipHistory->count())
                    <div x-show="showHistory" x-cloak
                         class="absolute top-12 z-10 w-64 h-64 overflow-hidden rounded bg-lime-700 border border-lime-600 text-white">
                        <div class="flex justify-between border-b border-lime-600">
                            <span>&nbsp;</span>
                            <span class="font-bold text-center">History</span>
                            <button type="button" class="btn-close" @click="showHistory = false">&times;</button>
                        </div>
                        <ul class="h-full overflow-y-auto pb-8">
                            @foreach($zipHistory as $zipHistoryEntry)
                                <li class="text-left">
                                    <button type="button"
                                            class="btn-zip-history"
                                            @click.once="$dispatch('zipChanged', {zip: '{{ $zipHistoryEntry->zip_code }}'}); showHistory = false">
                                        {{ $zipHistoryEntry->zip_code }} &mdash; {{ $zipHistoryEntry->reporting_area }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            @if($lastUpdatedAt)
                <span class="text-xs flex flex-col" title="last updated: {{ $lastUpdatedAt }}" wire:poll>
                <span title="{{ Carbon::make($lastUpdatedAt)->toDateTimeString() }} UTC">
                    {{ Carbon::make($lastUpdatedAt)->diffForHumans() }}
                </span>
            </span>
            @endif

            <div class="w-full text-center bg-lime-900">
                @error('zip') <span class="text-lime-500">{{ $message }}</span> @enderror
            </div>
        </form>

        @if($fetchError)
            <div class="w-full text-center bg-lime-900">
                <span class="text-lime-500">Invalid zip code</span>
            </div>
        @endif
    @endempty
</div>
