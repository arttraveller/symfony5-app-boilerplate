<?php

namespace App\Tests;

use App\Core\Entities\Post\Post;
use App\Core\Repositories\PostsRepository;
use App\DataFixtures\PostsFixtures;
use App\Tests\Other\LoginFunctional;

class PostsControllerCest
{
    use LoginFunctional;

    public function _before(FunctionalTester $I)
    {
    }

    public function testIndexOk(FunctionalTester $I)
    {
        $this->login($I);
        $I->amOnPage('/posts');
        $I->seeResponseCodeIs(200);
        $I->seeElement('div.posts');
        $I->see(PostsFixtures::LAST_POST_TITLE, 'td.post-title');
    }

    public function testCreateOk(FunctionalTester $I)
    {
        $this->login($I);
        $I->amOnPage('/posts/create');
        $I->seeResponseCodeIs(200);
        $I->seeElement('form', ['name' => 'create_post_form']);
        $I->fillField('Title', $title = 'New post title');
        $I->fillField('Text', $text = 'New post content');
        $I->click('Create');

        $I->seeCurrentUrlEquals('/posts');
        $user = $this->getCurrentUser($I);
        $I->seeInRepository(Post::class, [
            'user' => $user, 'title' => $title, 'text' => $text
        ]);
    }


    public function testShowOk(FunctionalTester $I)
    {
        /** @var PostsRepository $postsRepo */
        $postsRepo = $I->grabService(PostsRepository::class);
        $post = $postsRepo->getOneBy(['title' => PostsFixtures::LAST_POST_TITLE]);

        $this->login($I);
        $I->amOnPage('/posts/' . $post->getId());
        $I->seeResponseCodeIs(200);
        $I->see(PostsFixtures::LAST_POST_TITLE, 'h2');
        $I->see(PostsFixtures::LAST_POST_TEXT, 'div.post-content');
    }
}
