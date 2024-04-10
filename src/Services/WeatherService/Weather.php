<?php
declare(strict_types=1);

/**
 * @author Stefan Malte Homberg <zerobit@one.com>
 * @date 08.04.2024
 * @license MIT License
 * @file Weather.php
 */

namespace App\Services\WeatherService;

class Weather
{
    private $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function displayForecast(float $latitude, float $longitude): array {
        $response = $this->weatherService->getWeather($latitude, $longitude);
        return $response['daily'];
    }
}
