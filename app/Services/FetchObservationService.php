<?php

namespace App\Services;

use App\Models\Observation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Native\Laravel\Facades\Settings;

class FetchObservationService
{
    private $apiKey;
    private $zip;
    private $lastUpdatedAt;
    private $url = null;
    private mixed $newObservation = null;
    private bool $fetchError = false;

    public function __construct()
    {
        $this->apiKey = Settings::get('api_key');
        $this->zip = Settings::get('last_zip');
        $this->lastUpdatedAt = Settings::get('last_updated_at');

        if ($this->zip && $this->apiKey) {
            $this->url = "https://www.airnowapi.org/aq/observation/zipCode/current/?format=application/json&zipCode=$this->zip&distance=25&API_KEY=$this->apiKey";
        }
    }

    public function fetch(): bool
    {
        if (! $this->url) {
            return false;
        }

        $response = Http::get($this->url);

        $this->lastUpdatedAt = now();
        $this->newObservation = $response->json();

        if (is_array($this->newObservation) && empty($this->newObservation)) {
            $this->fetchError = true;

            return false;
        }

        Settings::set('last_updated_at', $this->lastUpdatedAt);
        Settings::set('last_observation', $this->newObservation);

        $this->saveObservation();

        return true;
    }

    public function getLastObservation(): Collection
    {
        return collect(Settings::get('last_observation'));
    }

    private function saveObservation(): void
    {
        foreach ($this->newObservation as $parameter) {
            $this->saveObservationParameter($parameter);
        }
    }

    private function saveObservationParameter(mixed $parameter): void
    {
        Log::info(json_encode($parameter));

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
    }
}
