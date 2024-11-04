<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Auth\Models\Account;
use Modules\Auth\Transformers\AccountResource;

class SaveAccountController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Account $account)
    {
        $account->saveBy($request->account);

        $account->is_saved = $account->isSavedBy($request->account);

        return AccountResource::make($account);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Account $account)
    {
        $account->unSaveBy($request->account);

        $account->is_saved = $account->isSavedBy($request->account);

        return AccountResource::make($account);
    }
}
