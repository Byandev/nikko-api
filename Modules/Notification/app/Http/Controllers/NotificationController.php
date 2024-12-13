<?php

namespace Modules\Notification\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Models\User;
use Modules\Notification\Transformers\NotificationResource;
use Spatie\QueryBuilder\QueryBuilder;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $notifications = QueryBuilder::for(DatabaseNotification::class)
            ->where('notifiable_type', User::class)
            ->where('notifiable_id', Auth::id())
            ->orderBy('created_at', 'DESC')
            ->paginate($request->input('per_page', 10));

        return NotificationResource::collection($notifications);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('notification::show');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
