<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApplicationTest extends WebTestCase
{
    public function testHomePageIsAccessible(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
    }

    public function testPostFormRedirectsIfNotLoggedIn(): void
    {
        $client = static::createClient();
        $client->request('GET', '/post/nouveau');
        $this->assertResponseRedirects('/login');
    }

    public function testApiPostsReturnsJson(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/posts', [], [], ['HTTP_ACCEPT' => 'application/ld+json']);
        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
    }
}