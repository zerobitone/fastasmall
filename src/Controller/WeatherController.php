<?php
declare(strict_types=1);
/**
 * @author Stefan Malte Homberg <zerobit@one.com>
 * @date 08.04.2024
 * @license MIT License
 * @file WeatherController.php
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\WeatherService\Weather;

class WeatherController extends AbstractController
{
    private Weather $weather;

    public function __construct(Weather $weather)
    {
        $this->weather = $weather;
    }

    #[Route('/weather', name: 'weather')]
    public function index(): Response
    {
        // TODO: Instead of passing the parameters literally to displayForecast, it is better to do it dynamically via user interaction
        $daily = $this->weather->displayForecast(52.5, 13.4);
        return $this->render('weather/index.html.twig', [
            'daily' => $daily,
        ]);
    }
}