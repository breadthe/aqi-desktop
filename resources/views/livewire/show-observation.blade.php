<?php

use Illuminate\Support\Facades\DB;
use function Livewire\Volt\{state, mount, on};
use App\Enums\Category;
use App\Models\Observation;

state(['lastZip' => null]);
state(['lastObservation' => null]);

mount(function () {
    $this->lastZip = $this->getLastZip();
    $this->lastObservation = $this->getLastObservation();
});

on(['observation-updated' => function () {
    $this->lastZip = $this->getLastZip();
    $this->lastObservation = $this->getLastObservation();
}]);

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
        ->get();
};
?>

<div class="flex flex-col p-2">
    @if($lastObservation)
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
        <div class="w-full text-center bg-lime-900">
            <span class="text-lime-500">No data yet</span>
        </div>
    @endif
</div>
