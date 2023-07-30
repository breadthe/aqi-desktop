<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use function Livewire\Volt\{state, mount, rules};

state(['settings' => null]);
state(['apiKey' => null]);
state(['newApiKey' => null]);
state(['isShowingForm' => false]);
state(['isShowingApiKey' => false]);

rules(['newApiKey' => 'required|string']);

mount(function () {
    $this->settings = Storage::json('settings.json');
    $this->apiKey = $this->settings['api_key'];
});

$save = function () {
    $this->validate();
    $this->isShowingApiKey = false;

    try {
        $this->apiKey = $this->newApiKey;
        $this->settings['api_key'] = $this->apiKey;

        Storage::put('settings.json', json_encode($this->settings));

        $this->newApiKey = null;
        $this->isShowingForm = false;
    } catch (\Exception $e) {
        Log::error($e->getMessage());
    }
};
?>

<div class="flex flex-col gap-4">
    @if($apiKey)
        <div class="flex gap-4">
            <span>✅</span>
            <button type="button" wire:click="$toggle('isShowingApiKey')" class="btn-link" title="Show API key">
                @if($isShowingApiKey)
                    🙈 hide
                @else
                    👀 show
                @endif
            </button>
            <button type="button" wire:click="$toggle('isShowingForm')" class="btn-link" title="Change API key">♻️ change</button>
        </div>

        @if($isShowingApiKey)
            <div class="flex gap-4">
                <span>{{ $apiKey }}</span>
            </div>
        @endif
    @endif

    @if($isShowingForm)
        <form wire:submit.prevent="save" class="w-full flex flex-col gap-4">
            <div class="flex sm:flex-row flex-col gap-2">
                <input type="text" id="api_key" wire:model="newApiKey" placeholder="API key">
                <button type="submit">save</button>
                <button type="button" wire:click="$toggle('isShowingForm')" class="btn-link">cancel</button>
            </div>

            <div class="w-full text-center bg-lime-900">
                @error('newApiKey') <span class="text-lime-500">{{ $message }}</span> @enderror
            </div>
        </form>
    @endif
</div>
