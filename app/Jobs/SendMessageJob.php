<?php

namespace App\Jobs;

use App\Models\Message;
use App\Services\MessageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class SendMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Message $message
    ) {}

    public function handle(MessageService $messageService): void
    {
        // mesaj karakter kuralı
        $messageService->validateMessage($this->message);

        // Burda webhooka POST isteği atıyoruz
        $response = Http::post(config('services.webhook.url'), [
           'phone'   => $this->message->phone,
            'message' => $this->message->content,
        ]);

        //  response 202 mi diye kontrol ediyoruz, değilse exception fırlatıyoruz
        if ($response->status() !== 202) {
            throw new \Exception('Failed.');
        }

        $messageId = config('app.env') === 'testing'
            ? (string) Str::uuid()
            : $response->json('messageId') ?? (string) Str::uuid();

        // DB’de mesajı sent olarak set et
        $messageService->markMessageAsSent($this->message, $messageId);

        // rediste cacheleme yapıyoruz
        Cache::put(
            "message:{$messageId}",
            [
                'id' => $this->message->id,
                'phone' => $this->message->phone,
                'sent_at' => now()->toDateTimeString(),
            ],
            now()->addHours(24)
        );
    }
    public function failed(Throwable $exception): void
    {
        // burda mail veya slack bildirimi ed yapabiliriz. ya da başka bir loglama servisi kulanabilriz
        logger()->error('Message sending job failed', [
            'message_id' => $this->message->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
