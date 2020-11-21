<?php

namespace App\Ui\Api\Controllers;

use App\Core\Commands\Posts\CreatePostCommand;
use App\Core\Commands\Posts\CreatePostHandler;
use App\Core\Repositories\PostsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PostsController extends ApiController
{
    private PostsRepository $postsRepo;


    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        PostsRepository $postsRepo
    )
    {
        parent::__construct($serializer, $validator);
        $this->postsRepo = $postsRepo;
    }


    /**
     * @Route("posts", name="posts_index", methods={"GET"})
     * @return Response
     */
    public function posts(Request $request): Response
    {
        $posts = $this->postsRepo->findAll();

        return $this->json([
            'data' => array_map(fn($post) => [
                'id' => $post->getId(),
                'title' => $post->getTitle(),
                'created_at' => $post->getCreatedAt(),
            ], $posts),
        ]);
    }


    /**
     * @Route("posts", name="posts_create", methods={"POST"})
     * @return Response
     */
    public function create(Request $request, CreatePostHandler $handler): Response
    {
        /** @var CreatePostCommand  $command */
        $command = $this->createCommand($request, CreatePostCommand::class);
        $this->validateCommand($command);
        $newPost = $handler->handle($command);

        return $this->json([
            'id' => $newPost->getId(),
            'title' => $newPost->getTitle(),
            'created_at' => $newPost->getCreatedAt(),
        ], 201);
    }
}