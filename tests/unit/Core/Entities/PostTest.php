<?php

namespace App\Tests;

use App\Domain\Entities\Post\Post;
use App\Tests\Other\TestUserFactory;

class PostTest extends \Codeception\Test\Unit
{
    /**
     * @var \App\Tests\UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }


    public function testCreateOk(): void
    {
        $user = TestUserFactory::registerByEmail($userEmail = 'abc@example.com');
        $newPost = new Post(
            $user,
            $title = 'Post title',
            $text = 'Post text'
        );

        $this->assertEquals($userEmail, $newPost->getUser()->getEmail());
        $this->assertEquals($title, $newPost->getTitle());
        $this->assertEquals($text, $newPost->getText());
        $this->assertInstanceOf(\DateTimeImmutable::class, $newPost->getCreatedAt());
    }

}