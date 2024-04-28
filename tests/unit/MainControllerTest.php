<?php
declare(strict_types=1);
/**
 * @author Stefan Malte Homberg <zerobit@one.com>
 * @date 28.04.2024
 * @license MIT License
 * @file MainControllerTest.php
 */

use App\Controller\MainController;
use App\Services\PostService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class MainControllerTest extends TestCase
{
    private $controller;
    private $postServiceMock;
    private $requestMock;
    private $containerMock;
    private $twigMock;

    protected function setUp(): void
    {
        $this->postServiceMock = $this->createMock(PostService::class);
        $this->controller = new MainController($this->postServiceMock);
        $this->requestMock = $this->createMock(Request::class);
        $this->containerMock = $this->createMock(Container::class);
        $this->twigMock = $this->createMock(Environment::class);

        // Container im Controller setzen und Twig Environment zur Verfügung stellen
        $this->controller->setContainer($this->containerMock);
        $this->containerMock->method('has')->willReturnMap([
            ['twig', true]
        ]);
        $this->containerMock->method('get')->willReturnMap([
            ['twig', $this->twigMock]
        ]);

        // Konfigurieren des Twig Mocks
        $this->twigMock->method('render')->willReturn('Fake content');
    }

    public function testIndexMethod(): void
    {
        $this->requestMock->method('getLocale')->willReturn('de');
        $mockPosts = [
            ['title' => 'Post 1', 'content' => 'Content 1'],
            ['title' => 'Post 2', 'content' => 'Content 2']
        ];
        $this->postServiceMock->method('getAllPosts')->willReturn($mockPosts);

        // Aufrufen der index Methode
        $response = $this->controller->index($this->requestMock);

        // Assertions
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('Fake content', $response->getContent());
    }
}