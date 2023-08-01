<?php

use function Livewire\Volt\{state, mount, on};
use App\Models\Observation;
use Illuminate\Support\Facades\DB;
use App\Enums\Category;

state(['lastZip' => null]);
state(['history' => null]);

mount(function () {
    $this->lastZip = $this->getLastZip();
    $this->history = $this->getHistoryForCurrentZip();
});

$getLastZip = function () {
    return Observation::query()->latest('updated_at')->first()->zip_code;
};

$getHistoryForCurrentZip = function () {
    if (!$this->lastZip) {
        return null;
    }

    $parameters = ['PM2.5', 'O3'];

    return Observation::query()
        ->select([
                     'id',
                     DB::raw('date_observed || hour_observed AS date_time_observed'),
                     'parameter_name',
                     'aqi',
                     'category_number',
                     'category_name',
                 ])
        ->where('zip_code', $this->lastZip)
        ->whereIn('parameter_name', $parameters)
        ->orderByDesc('date_observed')
        ->orderByDesc('hour_observed')
        ->orderByDesc('parameter_name') // PM2.5 before O3
        ->get()
        ->groupBy('date_time_observed');
};

$formatDateTime = function ($date_time_observed) {
    $date_time = explode(' ', $date_time_observed);
    $date = explode('-', $date_time[0]);
    $time = explode(':', $date_time[1]);

    $year = $date[0];
    $month = $date[1];
    $day = $date[2];
    $hour = $time[0];
    $minute = $time[1];

    $date_time = mktime($hour, $minute, 0, $month, $day, $year);

    return date('D, M jS, Y g:i A', $date_time);
};
?>

<div class="flex flex-col py-2 px-8">
    @if($history->count())
        <table class="w-full mx-auto max-w-sm">
            <thead class="sticky top-0 bg-neutral-900/70">
                <tr class="border-b">
                    <th class="text-left font-normal">History for <strong>{{ $lastZip }}</th>
                    <th class="grid grid-cols-2 gap-4">
                        <span class="text-left">PM2.5</span>
                        <span class="text-left">O3</span>
                    </th>
                </tr>
            </thead>
            <tbody>
            @foreach($history as $date_time_observed => $observation_tuple)
                <tr class="hover:bg-lime-600/30 border-b border-b-lime-600/30 border-t border-t-transparent">
                    <td class="pr-4 text-xs">
                        <span title="LST (Local Standard Time, no adjustment for Daylight Saving)">{{ $this->formatDateTime("$date_time_observed:00") }}</span>
                    </td>
                    <td class="grid grid-cols-2 gap-4">
                        @foreach($observation_tuple as $observation)
                            <div class="flex gap-2">
                                <div class="" title="{{ $observation->category_name }}">
                                    {{ Category::from($observation->category_number)->getEmoji() }}
                                </div>
                                <div class="" title="{{ $observation->category_name }}">
                                    {{ $observation->aqi }}
                                </div>
                            </div>
                        @endforeach
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="w-full text-center bg-lime-900">
            <span class="text-lime-500">No history yet</span>
        </div>
    @endif
</div>
