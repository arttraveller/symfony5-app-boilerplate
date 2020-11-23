<?php

namespace App\Core\Commands\Posts;

use App\Core\Entities\Post\Post;
use App\Core\Repositories\PostsRepository;
use App\Core\Repositories\UsersRepository;
use Symfony\Component\Security\Core\Security;

class CreatePostHandler
{
    private PostsRepository $postsRepo;
    private Security $security;
    private UsersRepository $usersRepo;


    public function __construct(PostsRepository $postsRepo, Security $security, UsersRepository $usersRepo)
    {
        $this->postsRepo = $postsRepo;
        $this->security = $security;
        $this->usersRepo = $usersRepo;
    }


    public function handle(CreatePostCommand $command): Post
    {
        $userId = $this->security->getUser()->getId();
        $user = $this->usersRepo->getOneById($userId);
        $post = new Post($user, $command->title, $command->text);
        $this->postsRepo->add($post);

        return $post;
    }
}
