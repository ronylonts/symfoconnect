<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // 1. Création de Roland
        $roland = new User();
        $roland->setEmail('test@test.com');
        $roland->setUsername('roland');
        $roland->setPassword($this->passwordHasher->hashPassword($roland, 'password'));
        $roland->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($roland);

        // 2. Données des autres utilisateurs
        $usersData = [
            ['email' => 'admin@symfoconnect.local', 'username' => 'admin', 'roles' => ['ROLE_ADMIN']],
            ['email' => 'alice@symfoconnect.local', 'username' => 'alice', 'roles' => []],
            ['email' => 'bob@symfoconnect.local', 'username' => 'bob', 'roles' => []],
        ];

        $users = ['roland' => $roland];

        foreach ($usersData as $userData) {
            $user = new User();
            $user->setEmail($userData['email']);
            $user->setUsername($userData['username']);
            $user->setRoles($userData['roles']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            if (method_exists($user, 'setCreatedAt')) {
                $user->setCreatedAt(new \DateTimeImmutable());
            }
            $manager->persist($user);
            $users[$userData['username']] = $user;
        }

        // 3. Les 15 posts de Roland + les 4 posts de l'enseignant
        $allPosts = [];
        
        // On prépare les 15 de Roland
        for ($i = 1; $i <= 15; $i++) {
            $allPosts[] = ['user' => $roland, 'content' => 'Contenu du post numéro ' . $i];
        }
        
        // On ajoute les 4 de l'enseignant
        $allPosts[] = ['user' => $users['admin'], 'content' => 'Bienvenue sur Symfoconnect !'];
        $allPosts[] = ['user' => $users['alice'], 'content' => 'Déjeuner en terrasse...'];
        $allPosts[] = ['user' => $users['alice'], 'content' => 'Nouvelle randonnée au sommet !'];
        $allPosts[] = ['user' => $users['bob'], 'content' => 'Soirée entre amis autour d’un café.'];

        foreach ($allPosts as $data) {
            $post = new Post();
            $post->setContent($data['content']);
            $post->setCreatedAt(new \DateTimeImmutable());
            
            // TEST : Est-ce qu'on utilise setAuthor ou setUser ?
            if (method_exists($post, 'setAuthor')) {
                $post->setAuthor($data['user']);
            } else {
                $post->setUser($data['user']);
            }

            $manager->persist($post);
        }

        $manager->flush();
    }
}