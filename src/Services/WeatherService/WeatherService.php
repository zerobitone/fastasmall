<?php
declare(strict_types=1);
/**
 * @author Stefan Malte Homberg <zerobit@one.com>
 * @date 08.04.2024
 * @license MIT License
 * @file WeatherService.php
 */

namespace App\Services\WeatherService;
class WeatherService
{
    public function __construct(private readonly HttpService $httpService,
                                private readonly int         $days,
                                private readonly string      $temperatureUnit)
    {
    }

    public function getWeather(float $lat, float $lng)
    {
        // TODO: Instead of passing the parameters literally in 'query' to 'timezone' => 'Europe/Berlin', it is better to do it dynamically via user interaction
        return $this->httpService->request('GET',
            'https://api.open-meteo.com/v1/forecast',
            ['query' => [
                'latitude' => $lat,
                'longitude' => $lng,
                'daily' => 'temperature_2m_max,temperature_2m_min',
                'timezone' => 'Europe/Berlin',
                'forecast_days' => $this->days,
                'temperature_unit' => $this->temperatureUnit,
            ]]
        )->toArray();
    }
}