<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{
    #[Route('/post/nouveau', name: 'app_post_nouveau')]
    public function nouveau(
        Request $request,
        EntityManagerInterface $em,
        \App\Repository\UserRepository $userRepository
    ): Response {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneBy(['username' => 'roland']);
            $post->setAuthor($user);
            $post->setCreatedAt(new \DateTimeImmutable());
            $em->persist($post);
            $em->flush();

            $this->addFlash('success', 'Post créé avec succès !');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('post/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}