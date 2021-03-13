<?php

namespace App\Shared\DataFixtures;

use App\Post\Domain\Post;
use App\User\Application\QueryServices\UsersQueryServiceInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PostsFixtures extends Fixture implements DependentFixtureInterface
{
    public const LAST_POST_TITLE = 'Last post';
    public const LAST_POST_TEXT = 'Last post content here...';

    private UsersQueryServiceInterface $usersQueryService;


    public function __construct(UsersQueryServiceInterface $usersQueryService)
    {
        $this->usersQueryService = $usersQueryService;
    }

    public function getDependencies()
    {
        return [
            UsersFixtures::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $post = $this->createPost();
            $manager->persist($post);
        }
        $post = $this->createPost(self::LAST_POST_TITLE, self::LAST_POST_TEXT);
        $manager->persist($post);

        $manager->flush();
    }

    private function createPost($title = 'Random post', $text = 'Post content here...'): Post
    {
        $user = $this->usersQueryService->getOneByEmail(UsersFixtures::CONFIRMED_USER_EMAIL);

        return new Post($user, $title, $text);
    }

}
