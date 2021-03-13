<?php

namespace App\Post\Application;

use App\Post\Domain\Post;

interface PostsRepositoryInterface
{
    public function create(Post $post): void;

    public function update(Post $post): void;

    public function delete(Post $post): void;
}
