<?php
declare(strict_types=1);
/**
 * @author Stefan Malte Homberg <zerobit@one.com>
 * @date 28.04.2024
 * @license MIT License
 * @file PostServiceTest.php
 */

use App\Services\PostService;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

class PostServiceTest extends TestCase
{
    private $entityManagerMock;
    private $postService;
    private $postRepositoryMock;

    protected function setUp(): void
    {
        // EntityManager mocken
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);

        // Repository mocken als EntityRepository
        $this->postRepositoryMock = $this->createMock(EntityRepository::class);

        // PostService mit dem EntityManager mock initialisieren
        $this->postService = new PostService($this->entityManagerMock);

        // Konfiguration des EntityManager Mocks, um das Post Repository zurückzugeben
        $this->entityManagerMock->method('getRepository')->willReturn($this->postRepositoryMock);
    }

    public function testGetAllPosts(): void
    {
        // Erwartungen für die Repository-Methode findAll()
        $this->postRepositoryMock->expects($this->once())->method('findAll')->willReturn([]);

        // Aufrufen der Methode
        $posts = $this->postService->getAllPosts();

        // Assertions
        $this->assertIsArray($posts);
    }

    public function testCreatePost(): void
    {
        $post = new Post();

        // Erwartungen für die EntityManager-Methoden
        $this->entityManagerMock->expects($this->once())->method('persist')->with($post);
        $this->entityManagerMock->expects($this->once())->method('flush');

        // Aufrufen der Methode
        $this->postService->createPost($post);
    }

    public function testUpdatePost(): void
    {
        $post = new Post();

        // Erwartungen für die EntityManager-Methode flush()
        $this->entityManagerMock->expects($this->once())->method('flush');

        // Aufrufen der Methode
        $this->postService->updatePost($post);
    }

    public function testDeletePost(): void
    {
        $post = new Post();

        // Erwartungen für die EntityManager-Methoden
        $this->entityManagerMock->expects($this->once())->method('remove')->with($post);
        $this->entityManagerMock->expects($this->once())->method('flush');

        // Aufrufen der Methode
        $this->postService->deletePost($post);
    }

    public function testGetPost(): void
    {
        $id = 1;
        $post = new Post();

        // Erwartungen für die Repository-Methode find()
        $this->postRepositoryMock->expects($this->once())->method('find')->with($id)->willReturn($post);

        // Aufrufen der Methode
        $result = $this->postService->getPost($id);

        // Assertions
        $this->assertSame($post, $result);
    }
}