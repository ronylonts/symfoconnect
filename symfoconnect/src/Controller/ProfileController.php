<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route('/profil/{username}', name: 'app_profile')]
    public function index(string $username, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['username' => $username]);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur introuvable');
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profil/{username}/follow', name: 'app_follow')]
    public function follow(string $username, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $currentUser = $this->getUser();
        $userToFollow = $userRepository->findOneBy(['username' => $username]);

        if (!$userToFollow || $currentUser === $userToFollow) {
            return $this->redirectToRoute('app_profile', ['username' => $username]);
        }

        if ($currentUser->getFollowing()->contains($userToFollow)) {
            $currentUser->removeFollowing($userToFollow);
        } else {
            $currentUser->addFollowing($userToFollow);

            $notification = new Notification();
            $notification->setType('follow');
            $notification->setContent($currentUser->getUsername() . ' vous suit maintenant');
            $notification->setIsRead(false);
            $notification->setCreatedAt(new \DateTimeImmutable());
            $notification->setRecipient($userToFollow);
            $em->persist($notification);
        }

        $em->flush();
        return $this->redirectToRoute('app_profile', ['username' => $username]);
    }
}