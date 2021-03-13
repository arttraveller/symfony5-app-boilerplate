<?php

namespace App\Tests;

use App\Post\Application\QueryServices\PostsQueryServiceInterface;
use App\Post\Domain\Post;
use App\Shared\DataFixtures\PostsFixtures;
use App\Shared\DataFixtures\UsersFixtures;
use App\Tests\Other\LoginFunctional;

class PostsControllerCest
{
    use LoginFunctional;

    public function testIndexOk(FunctionalTester $I)
    {
        $this->login($I);
        $I->amOnPage('/posts');
        $I->seeResponseCodeIs(200);
        $I->seeElement('div.posts');
        $I->see(PostsFixtures::LAST_POST_TITLE, 'td.post-title');
        $I->see(UsersFixtures::getConfirmedUserFullName(), 'td.post-author');
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
        /** @var PostsQueryServiceInterface $postsRepo */
        $postsQs = $I->grabService(PostsQueryServiceInterface::class);
        $post = $postsQs->getOneBy(['title' => PostsFixtures::LAST_POST_TITLE]);

        $this->login($I);
        $I->amOnPage('/posts/' . $post->getId());
        $I->seeResponseCodeIs(200);
        $I->see(PostsFixtures::LAST_POST_TITLE, 'h2');
        $I->see(PostsFixtures::LAST_POST_TEXT, 'div.post-content');
    }
}
