<?php
declare(strict_types=1);
/**
 * @author Stefan Malte Homberg <zerobit@one.com>
 * @date 28.04.2024
 * @license MIT License
 * @file LoginControllerTest.php
 */

namespace App\Tests\Controller;

use App\Controller\LoginController;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class LoginControllerTest extends KernelTestCase
{
    private $controller;
    private $authUtilsMock;
    private $twigMock;
    private $originalExceptionHandler;

    protected function setUp(): void
    {
        self::bootKernel();

        // Sichern und temporäres Entfernen des globalen Exception-Handlers
        $this->originalExceptionHandler = set_exception_handler(null);

        // Mock-Objekte erstellen
        $this->authUtilsMock = $this->createMock(AuthenticationUtils::class);
        $this->twigMock = $this->createMock(Environment::class);

        // Container einrichten und Mocks injizieren
        $container = static::getContainer();
        $container->set('twig', $this->twigMock);
        $container->set('security.authentication_utils', $this->authUtilsMock);

        // Controller instanziieren und Container setzen
        $this->controller = new LoginController();
        $this->controller->setContainer($container);
    }

    public function testIndexMethod(): void
    {
        // Mocks konfigurieren
        $this->authUtilsMock->method('getLastAuthenticationError')->willReturn(null);
        $this->authUtilsMock->method('getLastUsername')->willReturn('testUser');
        $this->twigMock->method('render')->willReturn('Rendered HTML content');

        // Index-Methode des Controllers mit dem benötigten AuthenticationUtils Mock aufrufen
        $response = $this->controller->index($this->authUtilsMock);

        // Antwort überprüfen
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('Rendered HTML content', $response->getContent());
    }

    protected function tearDown(): void
    {
        // Entfernen aller Exception-Handler, die von Symfony eingestellt wurden
        while ($currentHandler = set_exception_handler(null)) {
            restore_exception_handler();
        }

        // Sicherstellen, dass der Kernel nach Tests heruntergefahren wird
        self::ensureKernelShutdown();
        parent::tearDown();
    }
}