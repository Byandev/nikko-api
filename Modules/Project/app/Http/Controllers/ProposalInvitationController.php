<?php

namespace Modules\Project\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Project\Enums\ProposalInvitationStatus;
use Modules\Project\Models\ProposalInvitation;
use Modules\Project\Transformers\ProposalInvitationResource;
use Spatie\QueryBuilder\QueryBuilder;

class ProposalInvitationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = QueryBuilder::for(ProposalInvitation::class)
            ->where('account_id', $request->account->id)
            ->where('status', ProposalInvitationStatus::PENDING->value)
            ->allowedFilters([
                'project_id',
            ])
            ->allowedIncludes([
                'project',
                'project.account.user',
                'project.account.user.avatar',
            ])
            ->paginate($request->per_page ?? 10);

        return ProposalInvitationResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function update(Request $request, ProposalInvitation $invitation)
    {
        $data = $request->validate([
            'rejection_message' => 'required|string',
            'status' => 'required|in:'.ProposalInvitationStatus::REJECTED->value,
        ]);

        $invitation->update($data);

        $invitation->fresh()->load([
            'project' => [
                'account' => [
                    'user' => ['avatar'],
                ],
            ],
        ]);

        return ProposalInvitationResource::make($invitation);
    }

    /**
     * Show the specified resource.
     */
    public function show(Request $request, ProposalInvitation $invitation)
    {
        $invitation->load([
            'project' => [
                'account' => [
                    'user' => ['avatar'],
                ],
            ],
        ]);

        if ($request->account?->id != $invitation->account_id) {
            return response(['message' => 'Forbidden'], 403);
        }

        return ProposalInvitationResource::make($invitation);
    }
}
