<?php

use Illuminate\Support\Facades\DB;
use function Livewire\Volt\{state, boot};
use App\Enums\Category;
use App\Models\Observation;
use Native\Laravel\Facades\Settings;

state(['lastZip' => null]);
state(['lastObservation' => null]);

boot(function () {
    $this->lastZip = Settings::get('last_zip');
    $this->lastObservation = $this->getLastObservation();
});

$getLastZip = function()
{
    return Observation::query()->latest('updated_at')->first()->zip_code;
};

$getLastObservation = function()
{
    if (! $this->lastZip) {
        return null;
    }

    $pm25 = DB::table("observations")
        ->latest("updated_at")
        ->where("zip_code", $this->lastZip)
        ->where("parameter_name", "O3")
        ->limit(1);

    return DB::table("observations")
        ->latest("updated_at")
        ->where("zip_code", $this->lastZip)
        ->where("parameter_name", "PM2.5")
        ->limit(1)
        ->union($pm25)
        ->orderByDesc("parameter_name")
        ->get();
};
?>

<div class="flex flex-col gap-4 p-2" wire:poll>
    @if($lastObservation)
        <div class="w-full text-center font-bold">
            {{ "{$lastObservation[0]->reporting_area}, {$lastObservation[0]->state_code}" }}
        </div>
        <div class="w-full flex justify-center gap-8">
            @foreach($lastObservation as $parameter)
                <div class="flex flex-col items-center w-1/4 gap-2">
                    <div
                        class="flex items-center justify-center w-16 h-16 p-4 aspect-square rounded-full text-xl"
                        style="color: {{ Category::from($parameter->category_number)->getTextColor() }}; background-color: {{ Category::from($parameter->category_number)->getBgColor() }}"
                    >
                        {{ $parameter->aqi }}
                    </div>
                    <div class="flex flex-col gap-2 text-center">
                        <strong>{{ $parameter->parameter_name }}</strong>
                        <span>{{ $parameter->category_name }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="w-full text-center bg-lime-900 rounded">
            <span class="text-lime-500">No data yet</span>
        </div>
    @endif
</div>
