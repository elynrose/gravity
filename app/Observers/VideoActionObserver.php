<?php

namespace App\Observers;

use App\Models\Video;
use App\Notifications\DataChangeEmailNotification;
use Illuminate\Support\Facades\Notification;

class VideoActionObserver
{
    public function created(Video $model)
    {
        $data  = ['action' => 'created', 'model_name' => 'Video'];
        $users = \App\Models\User::whereHas('roles', function ($q) {
            return $q->where('title', 'Admin');
        })->get();
        Notification::send($users, new DataChangeEmailNotification($data));
    }

    public function updated(Video $model)
    {
        $data  = ['action' => 'updated', 'model_name' => 'Video'];
        $users = \App\Models\User::whereHas('roles', function ($q) {
            return $q->where('title', 'Admin');
        })->get();
        Notification::send($users, new DataChangeEmailNotification($data));
    }

    public function deleting(Video $model)
    {
        $data  = ['action' => 'deleted', 'model_name' => 'Video'];
        $users = \App\Models\User::whereHas('roles', function ($q) {
            return $q->where('title', 'Admin');
        })->get();
        Notification::send($users, new DataChangeEmailNotification($data));
    }
}
