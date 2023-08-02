<?php
use App\Enums\Category;
?>

<div class="flex gap-2">
    @isset($parameter)
        <div class="" title="{{ $parameter->category_name }}">
            {{ Category::from($parameter->category_number)->getEmoji() }}
        </div>
        <div class="" title="{{ $parameter->category_name }}">
            {{ $parameter->aqi }}
        </div>
    @else
        <div class="text-center" title="No data for {{ $parameter_name }}">
            &mdash;
        </div>
    @endisset
</div>
