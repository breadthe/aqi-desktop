<?php

namespace App\Enums;

use Exception;

enum Category: int
{
    case Good = 1;
    case Moderate = 2;
    case Unhealthy_for_Sensitive_Groups = 3;
    case Unhealthy = 4;
    case Very_Unhealthy = 5;
    case Hazardous = 6;
    case Unavailable = 7;

    /**
     * @see https://docs.airnowapi.org/aq101
     * @throws Exception
     */
    public function getTextColor(): string
    {
        return match ($this->value) {
            self::Good->value,
            self::Moderate->value,
            self::Unavailable->value => '#000000',

            self::Unhealthy_for_Sensitive_Groups->value,
            self::Unhealthy->value,
            self::Very_Unhealthy->value,
            self::Hazardous->value => '#ffffff',

            default => throw new Exception('Unexpected match value'),
        };
    }

    /**
     * @see https://docs.airnowapi.org/aq101
     * @throws Exception
     */
    public function getBgColor(): string
    {
        return match ($this->value) {
            self::Good->value => '#00e400',
            self::Moderate->value => '#ffff00',
            self::Unhealthy_for_Sensitive_Groups->value => '#ff7e00',
            self::Unhealthy->value => '#ff0000',
            self::Very_Unhealthy->value => '#8f3f97',
            self::Hazardous->value => '#7e0023',
            self::Unavailable->value => '#cccccc',
            default => throw new Exception('Unexpected match value'),
        };
    }

    /**
     * @see https://docs.airnowapi.org/aq101
     * @throws Exception
     */
    public function getAqiNumberRange(): array
    {
        return match ($this->value) {
            self::Good->value => ['lower' => 0, 'upper' => 50],
            self::Moderate->value => ['lower' => 51, 'upper' => 100],
            self::Unhealthy_for_Sensitive_Groups->value => ['lower' => 101, 'upper' => 150],
            self::Unhealthy->value => ['lower' => 151, 'upper' => 200],
            self::Very_Unhealthy->value => ['lower' => 201, 'upper' => 300],
            self::Hazardous->value => ['lower' => 301, 'upper' => 500],
            self::Unavailable->value => ['lower' => null, 'upper' => null],
            default => throw new Exception('Unexpected match value'),
        };
    }

    /**
     * @throws Exception
     */
    public function getEmoji(): string
    {
        return match ($this->value) {
            self::Good->value => '🟢',
            self::Moderate->value => '🟡',
            self::Unhealthy_for_Sensitive_Groups->value => '🟠',
            self::Unhealthy->value => '🔴',
            self::Very_Unhealthy->value => '🟣',
            self::Hazardous->value => '🟤',
            self::Unavailable->value => '⚪️',
            default => throw new Exception('Unexpected match value'),
        };
    }
}
