<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use App\Models\Avatar;
use App\Models\SendToOpenAi;
use App\Models\GenerateAvatar;
use App\Models\Audio;
use Log;



class RunVideo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-video';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kick off the processing of a video';

    /**
     * Execute the console command.
     */
    public function handle()
    {
         //get the project id 
         $project = Project::where('status', 'new')->first();
       
         if(!$project){
            \Log::info('No new projects to process');
            
            //Process failed projects
            $project = Project::where('status', 'failed')->first();
            if(!$project){
                \Log::info('No failed projects to process');
                return;
            } else {
                //get the audio for the failed project and trigger processing
                $audio = Audio::where('project_id', $project->id)->first();
                $audio->completed = 2; //2 means processing
                $audio->save();
                return;
            }

        }

         $project->status = 'avatar';

         //Get the avatar with the project id
         $avatar = Avatar::where('project_id', $project->id)->first();
 
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

            if($path){
                $avatar->prompt= $prompt;
                $avatar->avatar_url = $path;
                $avatar->completed = 1;
                $avatar->save();

                $project->status = 'audio';
                $project->save();

                //Trigger audio processing by updating the audio completed status
                $audio = Audio::where('project_id', $project->id)->first();
                if(!empty($audio->audio_url) && $audio->completed == 0){
                    $audio->completed = 2; //2 means processing
                    $audio->save();
                } else {
                    $audio->completed = 2; //2 means processing
                    $audio->save();
                }
                \Log::info('Avatar processing complete for Project:'.$project->id);

            } else {

                \Log::info('Failed to save character avatar for Project:'.$model->id);
            }

         } catch (\Exception $e) {

            \Log::info('Failed to save character avatar for project:'.$model->id);
         }
 

             
 
    }
}
