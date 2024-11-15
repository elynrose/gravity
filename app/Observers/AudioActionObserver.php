<?php

namespace App\Observers;

use App\Models\Audio;
use App\Notifications\DataChangeEmailNotification;
use Illuminate\Support\Facades\Notification;

class AudioActionObserver
{
    public function created(Audio $model)
    {
        $data  = ['action' => 'created', 'model_name' => 'Audio'];
        $users = \App\Models\User::whereHas('roles', function ($q) {
            return $q->where('title', 'Admin');
        })->get();
        Notification::send($users, new DataChangeEmailNotification($data));
    }

    public function updated(Audio $model)
    {
        $data  = ['action' => 'updated', 'model_name' => 'Audio'];
        $users = \App\Models\User::whereHas('roles', function ($q) {
            return $q->where('title', 'Admin');
        })->get();
        Notification::send($users, new DataChangeEmailNotification($data));
    }

    public function deleting(Audio $model)
    {
        $data  = ['action' => 'deleted', 'model_name' => 'Audio'];
        $users = \App\Models\User::whereHas('roles', function ($q) {
            return $q->where('title', 'Admin');
        })->get();
        Notification::send($users, new DataChangeEmailNotification($data));
    }
}
