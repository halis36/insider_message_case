<?php

namespace App\Repositories;
use App\Models\Message;
use App\Repositories\Contracts\MessageRepositoryInterface;
use Illuminate\Support\Collection;
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
        $message->update([
            'status'     => 'sent',
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
