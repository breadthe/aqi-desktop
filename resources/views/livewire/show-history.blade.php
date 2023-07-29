<?php

use function Livewire\Volt\{state, mount, on};
use App\Models\Observation;

state(['lastZip' => null]);
state(['observationsForPm25' => null]);
state(['observationsForO3' => null]);

mount(function () {
    $this->lastZip = $this->getLastZip();
    $this->observationsForPm25 = $this->getObservationsFor('PM2.5');
    $this->observationsForO3 = $this->getObservationsFor('O3');
});

$getLastZip = function()
{
    return Observation::query()->latest('updated_at')->first()->zip_code;
};

$getObservationsFor = function(string $parameterName)
{
    if (! $this->lastZip) {
        return null;
    }

    return Observation::query()
        ->where('zip_code', $this->lastZip)
        ->where('parameter_name', $parameterName)
        ->orderByDesc('date_observed')
        ->orderByDesc('hour_observed')
        ->get();
};
?>

<div class="flex flex-col p-2">
    @if($observationsForPm25 || $observationsForO3)
        <div class="w-full text-center">History for <strong>{{ $lastZip }}</strong></div>
        <div class="w-full flex justify-center gap-8">
            @if($observationsForPm25)
                @include('_observations-table', ['parameter' => 'PM2.5', 'observations' => $observationsForPm25])
            @endif

            @if($observationsForO3)
                @include('_observations-table', ['parameter' => 'O3', 'observations' => $observationsForO3])
            @endif
        </div>
    @else
        <div class="w-full text-center bg-lime-900">
            <span class="text-lime-500">No data yet</span>
        </div>
    @endif
</div>
