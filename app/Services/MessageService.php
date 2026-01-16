<?php

namespace App\Services;
use App\Models\Message;
use App\Repositories\Contracts\MessageRepositoryInterface;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
class MessageService
{
    public function __construct(private MessageRepositoryInterface $messageRepository)
    {
        //
    }

    public function getUnsentMessages(int $limit = 2)
    {
        return $this->messageRepository->getUnsentMessages($limit);
    }

    public function validateMessage(Message $message): void
    {
        if (mb_strlen($message->content) > 100) {
            throw new InvalidArgumentException('Message content exceeds 100 characters.');
        }
    }

    public function markMessageAsSent(Message $message, string $messageId): void
    {
        $this->messageRepository->markAsSent($message, $messageId);
    }

    public function getSentMessages()
    {
        return $this->messageRepository->getSentMessages();
    }
}
