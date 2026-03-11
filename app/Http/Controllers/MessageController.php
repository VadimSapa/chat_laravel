<?php

namespace App\Http\Controllers;

use App\Repositories\MessageRepository;
use App\Services\MessageService;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * @param MessageService $service
     */
    public function __construct(
        private MessageService $service
    ) {}

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string'
        ]);

        $this->service->sendMessage(
            $request->receiver_id,
            $request->message
        );

        return back();
    }

    /**
     * @param MessageRepository $repo
     * @return mixed
     */
    public function unread(MessageRepository $repo)
    {
        return $repo->getUnreadMessages(auth()->id());
    }

    /**
     * @param Request $request
     * @param MessageRepository $repo
     * @return \Illuminate\Http\JsonResponse
     */
    public function read(Request $request, MessageRepository $repo)
    {
        $request->validate([
            'ids' => 'required|array'
        ]);
        $repo->markAsRead($request->ids);

        return response()->json(['status' => 'ok']);
    }
}
