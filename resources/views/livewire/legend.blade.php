<?php

use App\Services\FetchObservationService;

use function Livewire\Volt\{state, computed, boot};

state(['pm25' => null]);
state(['o3' => null]);
state(['pm25_category' => null]);
state(['o3_category' => null]);

$pm25_position = computed(fn() => floor($this->pm25 / 500 * 100));
$o3_position = computed(fn() => floor($this->o3 / 500 * 100));

boot(function (FetchObservationService $service) {
    $lastObservation = $service->getLastObservation();

    $pm25 = $lastObservation->filter(fn ($o) => $o['ParameterName'] === 'PM2.5');
    $o3 = $lastObservation->filter(fn ($o) => $o['ParameterName'] === 'O3');
    $this->pm25 = $pm25->value('AQI');
    $this->o3 = $o3->value('AQI');
    $this->pm25_category = $pm25->value('Category');
    $this->o3_category = $o3->value('Category');
});
?>

<aside class="flex flex-col gap-1" wire:poll>
    @if($pm25)
        <div class="gradient w-full h-6 opacity-50 hover:opacity-100 relative" title="PM2.5 — {{ $pm25 }} {{ $pm25_category['Name'] }}">
            <div class="marker absolute top-0 bg-black h-6 w-[2px]"
                 style="left: {{ $this->pm25_position }}%"></div>
            <div class="label absolute top-1 bg-white text-black text-xs px-0.5 border border-black rounded z-10"
                 style="left: calc({{ $this->pm25_position }}% - 11px)">
                {{ $pm25 }}
            </div>
        </div>
    @endif

    @if($o3)
        <div class="gradient w-full h-6 opacity-50 hover:opacity-100 relative" title="O3 — {{ $o3 }} {{ $o3_category['Name'] }}">
            <div class="marker absolute top-0 bg-black h-6 w-[2px]"
                 style="left: {{ $this->o3_position }}%"></div>
            <div class="label absolute top-1 bg-white text-black text-xs px-0.5 border border-black rounded z-10"
                 style="left: calc({{ $this->o3_position }}% - 11px)">
                {{ $o3 }}
            </div>
        </div>
    @endif
</aside>
