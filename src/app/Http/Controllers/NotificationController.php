<?php

namespace App\Http\Controllers;

use App\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAllRead()
    {
        auth()->user()->unreadnotifications()->update([
            "read_at" => now()
        ]);
        return redirect()->route("notifications");
    }

    public function deleteAll()
    {
        auth()->user()->notifications()->delete();
        return redirect()->route("notifications");
    }

    public function markRead(Notification $notification)
    {
        auth()->user()->notifications()->find($notification)->markAsRead();
        return redirect()->route("notifications");
    }

    public function delete(Notification $notification)
    {
        $notification->delete();
        return redirect()->route("notifications");
    }


}
