<?php

namespace App\MessageHandler;

use App\Message\NewMessageNotification;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
class NewMessageNotificationHandler
{
    public function __construct(private MailerInterface $mailer) {}

    public function __invoke(NewMessageNotification $notification): void
    {
        $email = (new Email())
            ->from('noreply@symfoconnect.com')
            ->to($notification->getRecipientEmail())
            ->subject('Nouveau message de ' . $notification->getSenderUsername())
            ->text('Vous avez reçu un nouveau message : ' . $notification->getContent());

        $this->mailer->send($email);
    }
}