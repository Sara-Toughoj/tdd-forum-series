<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class UserNotificationsController extends Controller
{
    public function destroy($user, $notification)
    {
        auth()->user()->notifications()->findOrFail($notification)->markAsRead();
    }

    public function index($user)
    {
        return auth()->user()->unreadNotifications;
    }
}
