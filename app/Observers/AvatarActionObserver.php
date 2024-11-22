<?php

namespace App\Observers;

use App\Models\Avatar;
use App\Notifications\DataChangeEmailNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\GenerateAvatar;
use App\Models\SendToOpenAi;
use App\Models\Project;

class AvatarActionObserver
{
    public function created(Avatar $model)
    {
        $data  = ['action' => 'created', 'model_name' => 'Avatar'];

        /*get the project id 
        $project_id = $model->project_id;

        //Get the avatar with the project id
        $avatar = Avatar::where('project_id', $model->project_id)->first();

        //get the project from the project id
        $project = Project::find($project_id);

        //get the prompt from the project
        $prompt = $project->prompt;
        
        $prompt_example = "Create an image of a { user input }, positioned against a vibrant { background context provided by user or default to your imagination}. The character should be prominently placed in the foreground, facing directly towards the viewer at eye level. The lighting is rich and detailed, emphasizing hyperrealistic textures and natural highlights, ensuring DSLR-like quality. The colors are vibrant, and the girl's features, especially her face and lips, should be clearly visible for facial recognition and animation purposes. Her gaze should be directly at the camera to maintain a strong focal point. Ensure a 16:9 aspect ratio, with the character as the main focus, well-lit, and no side profiles—just a full front-facing pose.";
        $refined_prompt = "Generate a refined prompt using the prompt provided by the user guided by the example prompt as follows: Prompt example to use: ".$prompt_example." Using the prompt example, refine this user prompt to generate an avatar of a character with a { background }. If a background is not defined, use context clues to determine the background. Here is the user prompt: ".$prompt. "Give me the new refined prompt.";

        //Send the refined prompt to the OpenAI API
        $send_to_openai = new SendToOpenAi;
       
        $new_prompt = $send_to_openai->prompt($refined_prompt);
       
        $generate_avatar = new GenerateAvatar;
        //Send the response to DALL-E API
        $dalle_response = $generate_avatar->sendToDalle($new_prompt);

        //Decode the response
        $image = $dalle_response['data'][0]['url'];

        // Save the generated image URL to the character's avatar and save it to S3
        try {
            $path = $avatar->addMediaFromUrl($image)
                            ->toMediaCollection('avatar', 's3', 'images')
                            ->getUrl();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save character avatar'], 500);
        }

            $avatar->prompt= $prompt;
            $avatar->avatar_url = $path;
            $avatar->completed = 1;
            $avatar->save();
            



        //Get the user from the project
        $users = $model->project->user;
       */
      //  Notification::send($users, new DataChangeEmailNotification($data));
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
