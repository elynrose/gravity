<?php

namespace App\Observers;

use App\Models\Video;
use App\Notifications\DataChangeEmailNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\GenerateVideo;
use App\Models\Project;
use App\Models\Avatar;
use App\Models\Audio;
use Log;
use Illuminate\Http\Request;



class VideoActionObserver
{
    public function created(Video $model)
    {
        $data  = ['action' => 'created', 'model_name' => 'Video'];
        $users = \App\Models\User::whereHas('roles', function ($q) {
            return $q->where('title', 'Admin');
        })->get();
       // Notification::send($users, new DataChangeEmailNotification($data));
    }

    public function updated(Video $model)
    {
        $data  = ['action' => 'updated', 'model_name' => 'Video', 'changed_field' => 'completed'];
       
        if ($model->isDirty('completed') && $model->completed == 2) {
        
                //Get the project id from the video model
                $project_id = $model->project_id;

                //Get the project from the project id
                $project = Project::find($project_id);

                //Get the avatar and audio with the project id
                $avatar = Avatar::where('project_id', $project_id)->first();
                $audio = Audio::where('project_id', $project_id)->first();
                $video = Video::where('project_id', $project_id)->first();

                $avatar_url = $avatar->avatar_url;
                $audio_url = $audio->audio_url;

                //Generate the video
                $generate_video = new GenerateVideo;
                $response = $generate_video->create($avatar_url, $audio_url);

                //Save the video URL to the video model and save it to S3
                try {
                    $model->video_code = $response['id'];
                    $model->completed = 1;
                    $model->save();

                    //Upate the project status to completed
                    $project->status = 'ready';
                    $project->save();

                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                }
          
            }

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
