<?php

namespace App\Repositories\Contracts;

use App\Models\Message;
use Illuminate\Support\Collection;

interface MessageRepositoryInterface
{
    public function getUnsentMessages(int $limit): Collection;

    public function markAsSent(Message $message, string $messageId): void;

    public function getSentMessages(): Collection;
}
