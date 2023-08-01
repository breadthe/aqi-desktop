<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use function Livewire\Volt\{state, mount, rules};
use App\Models\Observation;

state(['settings' => null]);
state(['apiKey' => null]);
state(['zip' => null]);
state(['newObservation' => null]);
state(['fetchError' => false]);

rules(['zip' => 'required|numeric|digits:5']);

mount(function () {
    $this->settings = Storage::json('settings.json');
    $this->apiKey = $this->settings['api_key'] ?? null;
    $this->zip = $this->settings['lastZip'] ?? null;
});

$fetch = function () {
    $this->fetchError = false;

    $this->validate();

    $this->newObservation = null;

    $url = "https://www.airnowapi.org/aq/observation/zipCode/current/?format=application/json&zipCode=$this->zip&distance=25&API_KEY=$this->apiKey";

    $this->saveZipCode();

    // @todo try catch
    $response = Http::get($url);

    // @todo catch invalid api key

    $this->newObservation = $response->json();

    if (is_array($this->newObservation) && empty($this->newObservation)) {
        $this->fetchError = true;

        return;
    }

    $this->saveObservation();

    $this->dispatch('observation-updated');
};

$saveZipCode = function () {
    $this->settings['lastZip'] = $this->zip;

    Storage::put('settings.json', json_encode($this->settings));
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
?>

<div class="flex flex-col p-2">
    <form wire:submit.prevent="fetch" class="w-full flex flex-col items-center gap-2">
        <div class="w-full flex items-center justify-center gap-2">
            <input type="text" id="zip" wire:model="zip" maxlength="5" placeholder="Zip code" class="">
            <button type="submit">
                <svg viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6">
                    <path fill-rule="evenodd"
                          d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                          clip-rule="evenodd"/>
                </svg>
            </button>
        </div>

        <div class="w-full text-center bg-lime-900">
            @error('zip') <span class="text-lime-500">{{ $message }}</span> @enderror
        </div>
    </form>

    @if($fetchError)
        <div class="w-full text-center bg-lime-900">
            <span class="text-lime-500">Invalid zip code</span>
        </div>
    @endif
</div>
