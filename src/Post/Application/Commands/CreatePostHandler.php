<?php

namespace App\Post\Application\Commands;

use App\Auth\Application\QueryServices\CurrentUserFetcherInterface;
use App\Post\Application\PostsRepositoryInterface;
use App\Post\Domain\Post;

class CreatePostHandler
{
    private PostsRepositoryInterface $postsRepo;
    private CurrentUserFetcherInterface $userFetcher;

    public function __construct(PostsRepositoryInterface $postsRepo, CurrentUserFetcherInterface $userFetcher)
    {
        $this->postsRepo = $postsRepo;
        $this->userFetcher = $userFetcher;
    }

    public function handle(CreatePostCommand $command): Post
    {
        $user = $this->userFetcher->getUser();
        $post = new Post($user, $command->title, $command->text);
        $this->postsRepo->create($post);

        return $post;
    }
}
