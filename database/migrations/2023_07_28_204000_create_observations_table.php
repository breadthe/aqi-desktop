<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * https://www.airnowapi.org/aq/observation/zipCode/current/?format=application/json&zipCode=60602&distance=25&API_KEY=XXX
    {
        "DateObserved": "2023-07-28 ",
        "HourObserved": 14,
        "LocalTimeZone": "CST",
        "ReportingArea": "Chicago",
        "StateCode": "IL",
        "Latitude": 41.964,
        "Longitude": -87.659,
        "ParameterName": "O3",
        "AQI": 90,
        "Category": {
            "Number": 2,
            "Name": "Moderate"
        }
    },
     */
    public function up(): void
    {
        Schema::create('observations', function (Blueprint $table) {
            $table->id();
            $table->string('zip_code');
            $table->date('date_observed')->comment('DateObserved');
            $table->unsignedTinyInteger('hour_observed')->comment('HourObserved');
            $table->char('local_time_zone', 4)->comment('LocalTimeZone');
            $table->string('reporting_area')->comment('ReportingArea');
            $table->char('state_code', 2)->comment('StateCode');
            $table->double('latitude', total: 8, places: 3)->comment('Latitude');
            $table->double('longitude', total: 8, places: 3)->comment('Longitude');

            $table->string('parameter_name')->comment('ParameterName');
            $table->unsignedTinyInteger('aqi')->comment('AQI');
            $table->unsignedTinyInteger('category_number')->comment('Category.Number');
            $table->string('category_name')->comment('Category.Name');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('observations');
    }
};
