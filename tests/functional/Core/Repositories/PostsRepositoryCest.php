<?php

namespace App\Tests;

use App\Core\Entities\Post\Post;
use App\Core\Repositories\PostsRepository;
use App\DataFixtures\PostsFixtures;

class PostsRepositoryCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function testRepoOk(FunctionalTester $I)
    {
        $postsRepo = $I->grabService(PostsRepository::class);
        $post = $postsRepo->getOneBy(['title' => PostsFixtures::POST1_TITLE]);
        $I->assertInstanceOf(Post::class, $post);
    }
}
