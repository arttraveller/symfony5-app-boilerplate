<?php

namespace App\Ui\Api\Controllers;

use App\Core\Repositories\PostsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends ApiController
{
    /**
     * @Route("posts", name="posts_index", methods={"GET"})
     * @return Response
     */
    public function posts(Request $request, PostsRepository $postsRepo): Response
    {
        $posts = $postsRepo->findAll();

        return $this->json([
            'data' => array_map(fn($post) => [
                'id' => $post->getId(),
                'title' => $post->getTitle(),
                'created_at' => $post->getCreatedAt(),
            ], $posts),
        ]);
    }
}