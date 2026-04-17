<?php

namespace App\Controller;

use App\Entity\Message;
use App\Message\NewMessageNotification;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class MessageController extends AbstractController
{
    #[Route('/messages', name: 'app_messages')]
    public function index(MessageRepository $messageRepository): Response
    {
        $user = $this->getUser();
        $messages = $messageRepository->findConversations($user);

        return $this->render('message/index.html.twig', [
            'conversations' => $messages,
        ]);
    }

    #[Route('/messages/{username}', name: 'app_conversation')]
    public function conversation(
        string $username,
        UserRepository $userRepository,
        MessageRepository $messageRepository,
        EntityManagerInterface $em,
        Request $request,
        MessageBusInterface $bus
    ): Response {
        $currentUser = $this->getUser();
        $otherUser = $userRepository->findOneBy(['username' => $username]);

        if (!$otherUser) {
            throw $this->createNotFoundException('Utilisateur introuvable');
        }

        $unread = $messageRepository->findUnreadMessages($currentUser, $otherUser);
        foreach ($unread as $msg) {
            $msg->setIsRead(true);
        }
        $em->flush();

        $messages = $messageRepository->findConversation($currentUser, $otherUser);

        if ($request->isMethod('POST')) {
            $content = $request->request->get('content');
            if ($content) {
                $message = new Message();
                $message->setContent($content);
                $message->setSender($currentUser);
                $message->setRecipient($otherUser);
                $message->setIsRead(false);
                $message->setCreatedAt(new \DateTimeImmutable());
                $em->persist($message);
                $em->flush();
                $bus->dispatch(new NewMessageNotification(
                    $otherUser->getEmail(),
                    $currentUser->getUsername(),
                    $content
                ));

                return $this->redirectToRoute('app_conversation', ['username' => $username]);
            }
        }

        return $this->render('message/conversation.html.twig', [
            'otherUser' => $otherUser,
            'messages' => $messages,
        ]);
    }
}