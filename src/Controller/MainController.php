<?php
declare(strict_types=1);
/**
 * @author Stefan Malte Homberg <zerobit@one.com>
 * @date 08.04.2024
 * @license MIT License
 * @file WeatherController.php
 */
namespace App\Controller;

use App\Services\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    private PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    #[Route('/', name: 'app_main')]
    public function index(Request $request): Response
    {
        $pageLang = $request->getLocale();
        $posts = $this->postService->getAllPosts();

        return $this->render('main/index.html.twig', [
            'pageLang' => $pageLang,
            'posts' => $posts,
        ]);
    }
}
