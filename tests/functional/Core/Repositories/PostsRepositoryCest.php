<?php

namespace App\Tests;

use App\Core\Entities\Post\Post;
use App\DataFixtures\PostsFixtures;

class PostsRepositoryCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function testRepoOk(FunctionalTester $I)
    {
        $I->seeInRepository(Post::class, [
            'title' => PostsFixtures::POST1_TITLE,
        ]);
    }
}
