<?php

namespace App\Observers;

use App\Models\Credit;
use App\Notifications\DataChangeEmailNotification;
use Illuminate\Support\Facades\Notification;

class CreditActionObserver
{
    public function created(Credit $model)
    {
        $data  = ['action' => 'created', 'model_name' => 'Credit'];
        $users = \App\Models\User::whereHas('roles', function ($q) {
            return $q->where('title', 'Admin');
        })->get();
        Notification::send($users, new DataChangeEmailNotification($data));
    }
}
