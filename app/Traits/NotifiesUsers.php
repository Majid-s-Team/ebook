<?php

namespace App\Traits;

use App\Models\Notification;

trait NotifiesUsers
{
    public function notifyUser($userId, $title, $message = null, $type = null)
    {
        Notification::create([
            'user_id' => $userId,
            'title'   => $title,
            'message' => $message,
            'type'    => $type,
        ]);
    }
}
