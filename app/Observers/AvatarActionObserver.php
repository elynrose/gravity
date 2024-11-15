<?php

namespace App\Observers;

use App\Models\Avatar;
use App\Notifications\DataChangeEmailNotification;
use Illuminate\Support\Facades\Notification;

class AvatarActionObserver
{
    public function created(Avatar $model)
    {
        $data  = ['action' => 'created', 'model_name' => 'Avatar'];
        $users = \App\Models\User::whereHas('roles', function ($q) {
            return $q->where('title', 'Admin');
        })->get();
        Notification::send($users, new DataChangeEmailNotification($data));
    }

    public function updated(Avatar $model)
    {
        $data  = ['action' => 'updated', 'model_name' => 'Avatar'];
        $users = \App\Models\User::whereHas('roles', function ($q) {
            return $q->where('title', 'Admin');
        })->get();
        Notification::send($users, new DataChangeEmailNotification($data));
    }

    public function deleting(Avatar $model)
    {
        $data  = ['action' => 'deleted', 'model_name' => 'Avatar'];
        $users = \App\Models\User::whereHas('roles', function ($q) {
            return $q->where('title', 'Admin');
        })->get();
        Notification::send($users, new DataChangeEmailNotification($data));
    }
}
