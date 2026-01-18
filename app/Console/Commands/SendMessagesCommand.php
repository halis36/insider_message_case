<?php

namespace App\Console\Commands;

use App\Jobs\SendMessageJob;
use App\Services\MessageService;
use Illuminate\Console\Command;

class SendMessagesCommand extends Command
{
    protected $signature = 'messages:send';
    protected $description = 'Send pending messages via queue';

    public function handle(MessageService $messageService): int
    {
        $this->info('Message sending started...');

        while (true) {
            // status = penfing olan 2 mesajı al
            $messages = $messageService->getUnsentMessages(2);

            // Gönderilecek mesaj kalmadıysa çık
            if ($messages->isEmpty()) {
                $this->info('No pending messages found.');
                break;
            }

            // Her mesaj için Job dispatch et
            foreach ($messages as $message) {
                SendMessageJob::dispatch($message);
            }

            $this->info('2 messages dispatched to queue.');

            // Rate limit (5 saniye)
            sleep(5);
        }

        $this->info('Message sending finished.');

        return Command::SUCCESS;
    }
}
