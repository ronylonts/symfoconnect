<?php

namespace App\Tests;

use App\Entity\Post;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    public function testPostContent(): void
    {
        $post = new Post();
        $post->setContent('Bonjour tout le monde');
        $this->assertEquals('Bonjour tout le monde', $post->getContent());
    }

    public function testPostAuthor(): void
    {
        $user = new User();
        $user->setUsername('roland');

        $post = new Post();
        $post->setAuthor($user);

        $this->assertEquals('roland', $post->getAuthor()->getUsername());
    }
}