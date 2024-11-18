<?php

namespace Modules\Project\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Project\Models\Proposal;
use Modules\Project\Transformers\ProposalResource;

class SaveProposalController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Proposal $proposal)
    {
        $proposal->saveBy($request->account);

        $proposal->is_saved = $proposal->isSavedBy($request->account);

        return ProposalResource::make($proposal);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Proposal $proposal)
    {
        $proposal->unSaveBy($request->account);

        $proposal->is_saved = $proposal->isSavedBy($request->account);

        return ProposalResource::make($proposal);
    }
}
