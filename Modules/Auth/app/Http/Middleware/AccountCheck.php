<?php

namespace Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Models\Account;

class AccountCheck
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ?string $type)
    {
        $accountId = $request->header('X-ACCOUNT-ID');

        if (! $accountId) {
            return response(['message' => 'Forbidden', 'details' => 'No Account ID'], 403);
        }

        $account = Account::where('id', $accountId)
            ->where('user_id', Auth::id())
            ->when($type, fn (Builder $query) => $query->where('type', $type))
            ->first();

        if (! $account) {
            return response(['message' => 'Forbidden', 'details' => 'Invalid account.'], 403);
        }

        $request->merge(['account' => $account]);

        return $next($request);
    }
}
