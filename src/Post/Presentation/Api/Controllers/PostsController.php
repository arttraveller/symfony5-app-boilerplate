<?php

namespace App\Post\Presentation\Api\Controllers;

use App\Post\Application\Commands\CreatePostCommand;
use App\Post\Application\Commands\CreatePostHandler;
use App\Post\Application\QueryServices\PostsQueryServiceInterface;
use App\Post\Domain\Post;
use App\Shared\Ui\Api\ApiController;
use HTMLPurifier;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends ApiController
{
    private PostsQueryServiceInterface $postsQueryService;

    public function __construct(PostsQueryServiceInterface $postsQueryService)
    {
        $this->postsQueryService = $postsQueryService;
    }

    /**
     * @Route("posts", name="posts_index", methods={"GET"})
     * @return Response
     */
    public function posts(Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $this->postsQueryService->findAllWithUser(),
            $request->query->getInt('page', 1),
            self::PER_PAGE_DEFAULT
        );

        return $this->json([
            'records' => array_map(fn($post) => [
                'id' => $post->getId(),
                'user' => [
                    'id' => $post->getUser()->getId(),
                    'full_name' => $post->getUser()->getName()->getFullName(),
                ],
                'title' => $post->getTitle(),
                'created_at' => $post->getCreatedAt(),
            ], (array)$pagination->getItems()),
            'pagination' => [
                'page' => $pagination->getCurrentPageNumber(),
                'per_page' => $pagination->getItemNumberPerPage(),
                'total' => $pagination->getTotalItemCount(),
            ],
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

    /**
     * @Route("posts/{id}", name="posts_show", methods={"GET"})
     * @return Response
     */
    public function show(Post $post, HtmlPurifier $purifier): Response
    {
        return $this->json([
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'text' => $purifier->purify($post->getText()),
            'created_at' => $post->getCreatedAt(),
        ], 200);
    }
}