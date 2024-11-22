<?php

namespace App\Observers;

use App\Models\Audio;
use App\Notifications\DataChangeEmailNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\GenerateAudio;
use App\Models\Project;
use App\Models\Video;
use Log;

class AudioActionObserver
{
    public function created(Audio $model)
    {
        $data  = ['action' => 'created', 'model_name' => 'Audio'];
       
    }

    public function updated(Audio $model)
    {
        $data  = ['action' => 'updated', 'model_name' => 'Audio'];
         
        //if completed is set to 2 then start audio processing
         if($model->completed == 2){

            //Get the project id from the audio model
            $project_id = $model->project_id;

            //Get the project from the project id
            $project = Project::find($project_id);

            //Get the script from the project
            $script = $project->script;
            $voice = $project->voice;
            
            $tone = "shimmer";

            if($tone == 'male'){
                $voice = 'alloy';
            }elseif($tone == 'female'){
                $voice = 'nova';
            }

            //start audio processing
            $generate_audio = new GenerateAudio;
            $audio_url = $generate_audio->textToSpeech($script, $tone);

            //Save the audio URL to the audio model and save it to S3
            try {
                $model->audio_url = $audio_url;
                $model->completed = 1;
                $model->save();

                //Trigger the video processing. Get the video that has this project id
                $video = Video::where('project_id', $project_id)->first();
                $video->completed = 2; //set completed to 2 to trigger video processing
                $video->save();

                //Set this model to 1 to indicate that audio processing is complete
                $model->completed = 1;
                $model->save();

                //Set project status to video
                $project->status = 'video';
                $project->save();


            } catch (\Exception $e) {

                $model->completed = 0;
                $model->save();

            }
            
        }
       // Notification::send($users, new DataChangeEmailNotification($data));
    }

    public function deleting(Audio $model)
    {
        $data  = ['action' => 'deleted', 'model_name' => 'Audio'];
        $users = \App\Models\User::whereHas('roles', function ($q) {
            return $q->where('title', 'Admin');
        })->get();
        
        //Notification::send($users, new DataChangeEmailNotification($data));
    }
}
