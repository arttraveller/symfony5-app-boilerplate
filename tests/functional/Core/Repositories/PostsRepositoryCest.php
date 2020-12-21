<?php

namespace App\Tests;

use App\Domain\Entities\Post\Post;
use App\Repositories\PostsRepository;
use App\DataFixtures\PostsFixtures;

class PostsRepositoryCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function testRepoOk(FunctionalTester $I)
    {
        $postsRepo = $I->grabService(PostsRepository::class);
        $post = $postsRepo->getOneBy(['title' => PostsFixtures::LAST_POST_TITLE]);
        $I->assertInstanceOf(Post::class, $post);
    }
}
