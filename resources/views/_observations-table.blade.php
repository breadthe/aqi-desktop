@php
    use App\Enums\Category;
@endphp

@props(['parameter', 'observations'])

<table class="w-1/2">
    <thead>
        <tr>
            <th colspan="5" class="text-center">{{ $parameter }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($observations as $observation)
            <tr>
                <td class="">
                    {{ $observation->date_observed }}
                </td>
                <td class="">
                    {{ $observation->hour_observed }}:00
                </td>
                <td class="">
                    {{ Category::from($observation->category_number)->getEmoji() }}
                </td>
                <td class="">
                    {{ $observation->aqi }}
                </td>
                <td class="">
                    <span>{{ $observation->category_name }}</span>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
