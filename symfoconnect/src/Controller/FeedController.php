<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FeedController extends AbstractController
{
    #[Route('/feed', name: 'app_feed')]
    public function index(PostRepository $postRepository): Response
    {
        $user = $this->getUser();
        $following = $user->getFollowing();

        if ($following->isEmpty()) {
            return $this->render('feed/index.html.twig', [
                'posts' => [],
                'empty' => true,
            ]);
        }

        $posts = $postRepository->findFeedPosts($user);

        return $this->render('feed/index.html.twig', [
            'posts' => $posts,
            'empty' => false,
        ]);
    }
}