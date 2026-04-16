<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
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
        EntityManagerInterface $em
    ): Response {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setAuthor($this->getUser());
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

    #[Route('/post/{id}/like', name: 'app_post_like')]
    public function like(Post $post, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if ($post->getLikes()->contains($user)) {
            $post->removeLike($user);
        } else {
            $post->addLike($user);
        }

        $em->flush();
        return $this->redirectToRoute('app_home');
    }

    #[Route('/post/{id}/supprimer', name: 'app_post_supprimer')]
    public function supprimer(Post $post, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('DELETE', $post);
        $em->remove($post);
        $em->flush();
        $this->addFlash('success', 'Post supprimé !');
        return $this->redirectToRoute('app_home');
    }
}