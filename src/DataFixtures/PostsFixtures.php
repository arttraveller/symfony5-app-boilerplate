<?php

namespace App\DataFixtures;

use App\Core\Entities\Post\Post;
use App\Core\Repositories\UsersRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PostsFixtures extends Fixture implements DependentFixtureInterface
{
    public const LAST_POST_TITLE = 'Last post';

    private UsersRepository $usersRepo;


    public function __construct(UsersRepository $usersRepo)
    {
        $this->usersRepo = $usersRepo;
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
        $post = $this->createPost(self::LAST_POST_TITLE);
        $manager->persist($post);

        $manager->flush();
    }

    private function createPost($title = 'Random post'): Post
    {
        $user = $this->usersRepo->getOneByEmail(UsersFixtures::CONFIRMED_USER_EMAIL);

        return new Post($user, $title, 'Post content here...');
    }

}
