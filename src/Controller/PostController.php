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
use App\Form\PostType;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{
    private PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    #[Route('/create-post', name: 'create-post')]
    public function createPost(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData(); // Stellen Sie sicher, dass Ihr Formular den richtigen Namen hat

            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('posts_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $post->setImgUrl($newFilename);
            }

            $this->postService->createPost($post);
            $this->addFlash('message', 'Insert Successfully');
            return $this->redirectToRoute('list-posts');
        }

        return $this->render('posts/post.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit-post/{id}', name: 'edit-post')]
    public function editPost(Request $request, $id): Response
    {
        $post = $this->postService->getPost((int)$id);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('posts_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $post->setImgUrl($newFilename);
            }

            $this->postService->updatePost($post);
            $this->addFlash('message', 'Updated Successfully');
            return $this->redirectToRoute('list-posts');
        }

        return $this->render('posts/post.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
        ]);
    }

    #[Route('/delete-post/{id}', name: 'delete-post')]
    public function deletePost($id): Response
    {
        $post = $this->postService->getPost($id);

        if (!$post) {
            throw $this->createNotFoundException('The post does not exist');
        }

        if ($post->getImgUrl()) {
            $filePath = $this->getParameter('posts_images_directory') . '/' . $post->getImgUrl();
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $this->postService->deletePost($post);
        $this->addFlash('message', 'Deleted Successfully.');
        return $this->redirectToRoute('list-posts');
    }

    #[Route('/posts', name: 'list-posts')]
    public function listAllPosts(): Response
    {
        $posts = $this->postService->getAllPosts();
        return $this->render('posts/index.html.twig', [
            'posts' => $posts,
        ]);
    }
}
