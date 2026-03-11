<?php

namespace App\Services;

use App\Models\Message;
use App\Repositories\MessageRepository;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;

class MessageService
{
    /**
     * @param MessageRepository $repository
     */
    public function __construct(
        private MessageRepository $repository
    ) {}

    /**
     * @param int $receiverId
     * @param string $text
     * @return Message
     */
    public function sendMessage(int $receiverId, string $text): Message
    {
        $message = $this->repository->create([
            'sender_id' => Auth::id(),
            'receiver_id' => $receiverId,
            'message' => $text
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return $message;
    }
}
