<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('test@test.com');
        $user->setUsername('roland');
        $user->setPassword('password');
        $user->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($user);

        for ($i = 1; $i <= 15; $i++) {
            $post = new Post();
            $post->setContent('Contenu du post numéro ' . $i);
            $post->setCreatedAt(new \DateTimeImmutable());
            $post->setAuthor($user);
            $manager->persist($post);
        }

        $manager->flush();
    }
}