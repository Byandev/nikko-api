<?php

namespace Modules\Chat\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Chat\Models\Channel;
use Modules\Chat\Transformers\ChannelResource;
use Modules\Project\Models\Proposal;

class ChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $channels = Channel::with(['members.avatar'])
            ->whereHas('members', function (Builder $builder) {
                $builder->where('users.id', Auth::id());
            })
            ->orderBy('last_activity_at', 'DESC')
            ->paginate($request->input('per_page', 10));

        return ChannelResource::collection($channels);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject_proposal_id' => 'required|exists:proposals,id',
        ]);

        $proposal = Proposal::find($request->post('subject_proposal_id'));
        $proposal->loadMissing(['project.account.user', 'account.user']);

        $channel = Channel::create([
            'subject_id' => $proposal->id,
            'subject_type' => Proposal::class,
            'title' => $proposal->project->title,
            'last_activity_at' => now(),
        ]);

        $channel->members()->sync([
            $proposal->account->user_id,
            $proposal->project->account->user_id,
        ]);

        return ChannelResource::make($channel->load(['members.avatar']));
    }

    /**
     * Show the specified resource.
     */
    public function show(Channel $channel)
    {
        $channel->load(['subject.project.account.user', 'members.model.avatar']);

        return ChannelResource::make($channel);
    }
}
