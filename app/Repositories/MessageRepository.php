<?php

namespace App\Repositories;
use App\Enums\MessageStatus;
use App\Models\Message;
use App\Repositories\Contracts\MessageRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class MessageRepository implements MessageRepositoryInterface
{
    public function getUnsentMessages(int $limit): Collection
    {
        return Message::query()
            ->where('status', 'pending')
            ->orderBy('id')
            ->limit($limit)
            ->get();
    }

    public function markAsSent(Message $message, string $messageId): void
    {
        if ($message->message_id) {
            return;
        }

        $message->update([
            'status'     => MessageStatus::SENT,
            'message_id' => $messageId,
            'sent_at'    => now(),
        ]);
    }

    public function getSentMessages(): Collection
    {
        return Message::query()
            ->where('status', 'sent')
            ->orderByDesc('sent_at')
            ->get();
    }
}
