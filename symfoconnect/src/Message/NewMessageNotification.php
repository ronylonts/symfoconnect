<?php

namespace App\Message;

class NewMessageNotification
{
    public function __construct(
        private string $recipientEmail,
        private string $senderUsername,
        private string $content
    ) {}

    public function getRecipientEmail(): string
    {
        return $this->recipientEmail;
    }

    public function getSenderUsername(): string
    {
        return $this->senderUsername;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}