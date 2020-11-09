<?php

namespace App\DataFixtures;

use App\Core\Entities\Post\Post;
use App\Core\Repositories\UsersRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PostsFixtures extends Fixture implements DependentFixtureInterface
{
    public const POST1_TITLE = 'First post';

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
        $post1 = $this->createPost1();
        $manager->persist($post1);

        $manager->flush();
    }

    private function createPost1(): Post
    {
        $user = $this->usersRepo->getOneByEmail(UsersFixtures::CONFIRMED_USER_EMAIL);

        return new Post($user, self::POST1_TITLE, 'Post 1 content here...');
    }

}
