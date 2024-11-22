<?php

namespace App\Observers;

use App\Models\Project;
use App\Notifications\DataChangeEmailNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\Audio;
use App\Models\Video;
use App\Models\Credit;
use App\Models\Avatar;


class ProjectActionObserver
{
    public function created(Project $model)
    {
        $data  = ['action' => 'created', 'model_name' => 'Project'];
        
        //Create records in Audio, Video, Credit and Avatar tables
        $audio = new Audio();
        $audio->project_id = $model->id;
        $audio->save();

        $video = new Video();
        $video->project_id = $model->id;
        $video->save();

        $avatar = new Avatar();
        $avatar->project_id = $model->id;
        $avatar->save();

        //Notification::send($users, new DataChangeEmailNotification($data));
    }

    public function updated(Project $model)
    {
        $data  = ['action' => 'updated', 'model_name' => 'Project'];
        $users = \App\Models\User::whereHas('roles', function ($q) {
            return $q->where('title', 'Admin');
        })->get();
        Notification::send($users, new DataChangeEmailNotification($data));
    }


    public function deleted(Project $model){
        $data  = ['action' => 'deleted', 'model_name' => 'Project', 'id' => $model->id];
        
            // Delete related entries in Avatar, Audio, and Videos tables
             Avatar::where('project_id', $data->id)->delete();
             Audio::where('project_id', $data->id)->delete();
             Video::where('project_id', $data->id)->delete();
    }
}
