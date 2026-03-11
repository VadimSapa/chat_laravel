<?php

namespace App\Repositories;

use App\Models\Message;

class MessageRepository
{
    /**
     * @param array $data
     * @return Message
     */
    public function create(array $data): Message
    {
        return Message::create($data);
    }

    /**
     * @param int $userId
     * @return mixed
     */
    public function getUnreadMessages(int $userId): mixed
    {
        $messages = Message::where('receiver_id', $userId)
            ->whereNull('read_at')
            ->orderBy('id')
            ->get(['id', 'message']);

        $this->markAsRead($messages->pluck('id')->all());

        return $messages;
    }

    /**
     * @param array $messageIds
     * @return void
     */
    public function markAsRead(array $messageIds): void
    {
        Message::whereIn('id', $messageIds)
            ->update(['read_at' => now()]);
    }
}
