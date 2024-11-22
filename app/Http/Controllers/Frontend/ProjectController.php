<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProjectRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\GenerateVideo;
use App\Models\UploadAudio;
use App\Models\Audio;

class ProjectController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('project_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $projects = Project::with(['user'])->get();

        return view('frontend.projects.index', compact('projects'));
    }

    public function create()
    {
        abort_if(Gate::denies('project_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.projects.create', compact('users'));
    }

    public function store(StoreProjectRequest $request)
    {
        //check the audio file type
       
    
        $request->validate([
            'audio' => 'required_if:inputMethod,recordVoice|mimes:mp3,mp4,wav,ogg,webm',
        ]);

        $new_audio = $request->audio;
   
    
        if ($request->inputMethod === 'textToSpeech') {

            $project = Project::create($request->all());

        } elseif ($request->inputMethod === 'recordVoice') {

            if ($request->hasFile('audio') && $request->file('audio')->isValid()) {

                $project = Project::create([
                    'name' => $request->name,
                    'prompt' => $request->prompt,
                    'status' => $request->status,
                    'inputMethod' => $request->inputMethod,
                    'user_id' => $request->user_id,
                ]);
                
                // Handle audio upload
                $uploadAudio = new UploadAudio;
                $mp3Path = $uploadAudio->new($new_audio);
    
                if ($project) {
                    $audio = Audio::where('project_id', $project->id)->first();
                    $audio->update([
                        'path' => $mp3Path,
                        'completed' => 0,
                    ]);
                }
            } else {
                return back()->withErrors(['audio' => 'Invalid audio file.']);
            }
        }
    
        return json_encode($project);
    }
    

    public function edit(Project $project)
    {
        abort_if(Gate::denies('project_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $project->load('user');

        return view('frontend.projects.edit', compact('project', 'users'));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update($request->all());

        return redirect()->route('frontend.projects.index');
    }

    public function show(Project $project)
    {
        abort_if(Gate::denies('project_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $project->load('user');

        return view('frontend.projects.show', compact('project'));
    }

    public function destroy(Project $project)
    {
        abort_if(Gate::denies('project_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $project->delete();
 

        return back();
    }

    public function massDestroy(MassDestroyProjectRequest $request)
    {
        $projects = Project::find(request('ids'));

        foreach ($projects as $project) {
            $project->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
