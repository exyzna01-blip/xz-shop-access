<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markRead(Request $request, Notification $notification)
    {
        $user = $request->user();
        abort_unless($user->role === 'ADMIN', 403);
        abort_unless($notification->to_user_id === $user->id, 403);

        $notification->update(['read_at' => now()]);
        return back();
    }
}
