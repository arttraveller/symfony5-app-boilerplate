<?php

namespace App\Tests;

use App\Core\Entities\Post\Post;
use App\Tests\Other\LoginFunctional;

class PostsControllerCest
{
    use LoginFunctional;

    public function _before(FunctionalTester $I)
    {
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
}
