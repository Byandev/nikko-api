<?php

namespace Modules\Chat\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Chat\Broadcasting\MessageSent;
use Modules\Chat\Models\Channel;
use Modules\Chat\Transformers\MessageResource;
use Modules\Media\Enums\MediaCollectionType;
use Modules\Media\Models\Media;

class ChatMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Channel $channel)
    {
        $messages = $channel->messages()
            ->with(['attachments', 'sender.avatar'])
            ->orderBy('created_at', 'DESC')
            ->paginate($request->input('per_page', 15));

        return MessageResource::collection($messages);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Channel $channel)
    {
        $request->validate([
            'content' => 'required_without:attachment_ids|string',
            'attachment_ids' => 'required_without:content|array',
            'attachment_ids.*' => 'required|exists:media,id',
        ]);

        $message = $channel->messages()->create([
            'content' => $request->post('content'),
            'sender_id' => Auth::id(),
        ]);

        if ($request->has('attachment_ids')) {
            Media::whereIn('id', $request->post('attachment_ids'))
                ->get()
                ->each(function (Media $media) use ($message) {
                    $media->move($message, MediaCollectionType::CHAT_MESSAGE_ATTACHMENTS->value);
                });
        }

        $channel->last_activity_at = Carbon::now();
        $channel->save();

        broadcast(new MessageSent($message));

        return MessageResource::make($message->loadMissing(['attachments', 'sender.avatar']));

    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('chat::show');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }
}
