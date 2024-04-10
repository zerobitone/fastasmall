<?php
declare(strict_types=1);
/**
 * @author Stefan Malte Homberg <zerobit@one.com>
 * @date 08.04.2024
 * @license MIT License
 * @file HttpService.php
 */

namespace App\Services\WeatherService;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class HttpService
{
    public function __construct(private readonly HttpClientInterface $httpClient){}
    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        return $this->httpClient->request($method, $url, $options);
    }
}