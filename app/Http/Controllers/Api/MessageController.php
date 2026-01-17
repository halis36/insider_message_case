<?php

namespace App\Http\Controllers\Api;

use App\Enums\MessageStatus;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'Message API',
    version: '1.0.0',
    description: 'Sent messages listing API'
)]
class MessageController extends Controller
{
    #[OA\Get(
        path: '/api/messages',
        tags: ['Messages'],
        summary: 'Get all messages',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful response',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer'),
                            new OA\Property(property: 'phone', type: 'string'),
                            new OA\Property(property: 'content', type: 'string'),
                            new OA\Property(property: 'status', type: 'string'),
                            new OA\Property(property: 'message_id', type: 'string'),
                            new OA\Property(property: 'sent_at', type: 'string', nullable: true),
                        ]
                    )
                )
            )
        ]
    )]
    public function index()
    {
        return response()->json(['data' => Message::all()]);
    }

    #[OA\Post(
        path: '/api/messages',
        tags: ['Messages'],
        summary: 'Create a new message',
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                'application/json' => new OA\JsonContent(
                    required: ['phone', 'content'],
                    properties: [
                        new OA\Property(property: 'phone', type: 'string', example: '+905332912156'),
                        new OA\Property(property: 'content', type: 'string', example: 'test message'),
                    ]
                )
            ]
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Message queued successfully'
            )
        ]
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string|max:20',
            'content' => 'required|string|max:100',
        ]);

        $message = Message::create([
            'phone' => $validated['phone'],
            'content' => $validated['content'],
            'status' => MessageStatus::PENDING,
        ]);

        return response()->json([
            'message' => 'Message queued successfully',
            'data' => $message,
        ], 201);
    }
}
