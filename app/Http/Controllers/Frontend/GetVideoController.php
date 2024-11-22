<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Project;
use GuzzleHttp\Client;
use App\Models\Video;
use App\Models\Audio;
use App\Models\Avatar;


class GetVideoController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    function getVideo(Request $request){
    
        $project_id = $request->id;
       
        //Get the project
        $project = Project::where('id', $project_id)->first();


        //Get the video
        $video = Video::where('project_id', $project_id)->first();
        
        if(!$project->video($project_id)->video_code){
            return response()->json(['message' => 'No video code']);
        }
        $code = $project->video($project_id)->video_code;
        $response = $this->client->get("https://api.d-id.com/talks/{$code}", [
            'headers' => [
                'accept' => 'application/json',
                'authorization' => 'Basic ' . env('DID_API_KEY'),
                'content-type' => 'application/json',
            ],
        ]);

        $video_result = json_decode($response->getBody()->getContents(), true); 

        if(isset($video_result['kind']) && $video_result['kind'] == 'NotFoundError'){
            return response()->json(['message' => 'Error getting video']);
            $video->completed = 0;
            $video->video_url = '';
            $video->save();

            $project->status = 'failed';
            $project->save();

            return false;
        }

        if($video_result['status'] == 'done'){
            $video_url = $video_result['result_url'];
            $video->video_url = trim($video_url);
            $video->completed = 1;
            $video->save();

            //check if all the other models are completed
            $audio = Audio::where('project_id', $project_id)->first();
            $avatar = Avatar::where('project_id', $project_id)->first();
            $video = Video::where('project_id', $project_id)->first();

            if($audio->completed == 1 && $avatar->completed == 1 && $video->completed == 1){
                $project->status = 'ready';
            }else{
                $project->status = 'new';
            }

            $project->save();

            
            return response()->json(['video_url' => $video_url, 'status' => $project->status, 'id' => $project_id]);
        }else{
            $video->completed = 0;
            $video->video_url = '';
            $video->save();
           return false;
        }

 
    }

}
