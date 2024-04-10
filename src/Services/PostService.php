<?php
declare(strict_types=1);
/**
 * @author Stefan Malte Homberg <zerobit@one.com>
 * @date 12.03.2024
 * @license MIT License
 * @file PostService.php
 */
namespace App\Service;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Scalar\Int_;

class PostService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAllPosts(): array
    {
        return $this->entityManager->getRepository(Post::class)->findAll();
    }

    public function createPost(Post $post)
    {
        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }

    public function updatePost(Post $post)
    {
        $this->entityManager->flush();
    }

    public function deletePost(Post $post)
    {
        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }

    public function getPost(Int $id)
    {
        return $this->entityManager->getRepository(Post::class)->find($id);
    }
}