<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyAudioRequest;
use App\Http\Requests\StoreAudioRequest;
use App\Http\Requests\UpdateAudioRequest;
use App\Models\Audio;
use App\Models\Project;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class AudiosController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('audio_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $audios = Audio::with(['project', 'media'])->get();

        return view('frontend.audios.index', compact('audios'));
    }

    public function create()
    {
        abort_if(Gate::denies('audio_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $projects = Project::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.audios.create', compact('projects'));
    }

    public function store(StoreAudioRequest $request)
    {
        $audio = Audio::create($request->all());

        if ($request->input('audio', false)) {
            $audio->addMedia(storage_path('tmp/uploads/' . basename($request->input('audio'))))->toMediaCollection('audio');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $audio->id]);
        }

        return redirect()->route('frontend.audios.index');
    }

    public function edit(Audio $audio)
    {
        abort_if(Gate::denies('audio_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $projects = Project::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $audio->load('project');

        return view('frontend.audios.edit', compact('audio', 'projects'));
    }

    public function update(UpdateAudioRequest $request, Audio $audio)
    {
        $audio->update($request->all());

        if ($request->input('audio', false)) {
            if (! $audio->audio || $request->input('audio') !== $audio->audio->file_name) {
                if ($audio->audio) {
                    $audio->audio->delete();
                }
                $audio->addMedia(storage_path('tmp/uploads/' . basename($request->input('audio'))))->toMediaCollection('audio');
            }
        } elseif ($audio->audio) {
            $audio->audio->delete();
        }

        return redirect()->route('frontend.audios.index');
    }

    public function show(Audio $audio)
    {
        abort_if(Gate::denies('audio_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $audio->load('project');

        return view('frontend.audios.show', compact('audio'));
    }

    public function destroy(Audio $audio)
    {
        abort_if(Gate::denies('audio_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $audio->delete();

        return back();
    }

    public function massDestroy(MassDestroyAudioRequest $request)
    {
        $audios = Audio::find(request('ids'));

        foreach ($audios as $audio) {
            $audio->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('audio_create') && Gate::denies('audio_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Audio();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
