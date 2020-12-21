<?php

namespace App\Domain\Commands\Posts;

use App\Domain\Entities\Post\Post;
use App\Domain\Fetchers\Interfaces\CurrentUserFetcherInterface;
use App\Domain\Repositories\Interfaces\PostsRepositoryInterface;

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
        $this->postsRepo->add($post);

        return $post;
    }
}
