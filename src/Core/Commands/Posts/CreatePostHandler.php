<?php

namespace App\Core\Commands\Posts;

use App\Core\Entities\Post\Post;
use App\Core\Fetchers\Interfaces\CurrentUserFetcherInterface;
use App\Core\Repositories\PostsRepository;

class CreatePostHandler
{
    private PostsRepository $postsRepo;
    private CurrentUserFetcherInterface $userFetcher;


    public function __construct(PostsRepository $postsRepo, CurrentUserFetcherInterface $userFetcher)
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
