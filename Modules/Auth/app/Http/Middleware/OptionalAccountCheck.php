<?php

namespace Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Models\Account;

class OptionalAccountCheck
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ?string $type)
    {
        $accountId = $request->header('X-ACCOUNT-ID');

        if ($accountId && Auth::check()) {
            $account = Account::where('id', $accountId)
                ->where('user_id', Auth::id())
                ->when($type, fn (Builder $query) => $query->where('type', $type))
                ->first();

            if ($account) {
                $request->merge(['account' => $account]);
            }
        }

        return $next($request);
    }
}
