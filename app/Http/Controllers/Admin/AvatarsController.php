<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyAvatarRequest;
use App\Http\Requests\StoreAvatarRequest;
use App\Http\Requests\UpdateAvatarRequest;
use App\Models\Avatar;
use App\Models\Project;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class AvatarsController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('avatar_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $avatars = Avatar::with(['project', 'media'])->get();

        return view('admin.avatars.index', compact('avatars'));
    }

    public function create()
    {
        abort_if(Gate::denies('avatar_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $projects = Project::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.avatars.create', compact('projects'));
    }

    public function store(StoreAvatarRequest $request)
    {
        $avatar = Avatar::create($request->all());

        if ($request->input('avatar', false)) {
            $avatar->addMedia(storage_path('tmp/uploads/' . basename($request->input('avatar'))))->toMediaCollection('avatar');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $avatar->id]);
        }

        return redirect()->route('admin.avatars.index');
    }

    public function edit(Avatar $avatar)
    {
        abort_if(Gate::denies('avatar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $projects = Project::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $avatar->load('project');

        return view('admin.avatars.edit', compact('avatar', 'projects'));
    }

    public function update(UpdateAvatarRequest $request, Avatar $avatar)
    {
        $avatar->update($request->all());

        if ($request->input('avatar', false)) {
            if (! $avatar->avatar || $request->input('avatar') !== $avatar->avatar->file_name) {
                if ($avatar->avatar) {
                    $avatar->avatar->delete();
                }
                $avatar->addMedia(storage_path('tmp/uploads/' . basename($request->input('avatar'))))->toMediaCollection('avatar');
            }
        } elseif ($avatar->avatar) {
            $avatar->avatar->delete();
        }

        return redirect()->route('admin.avatars.index');
    }

    public function show(Avatar $avatar)
    {
        abort_if(Gate::denies('avatar_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $avatar->load('project');

        return view('admin.avatars.show', compact('avatar'));
    }

    public function destroy(Avatar $avatar)
    {
        abort_if(Gate::denies('avatar_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $avatar->delete();

        return back();
    }

    public function massDestroy(MassDestroyAvatarRequest $request)
    {
        $avatars = Avatar::find(request('ids'));

        foreach ($avatars as $avatar) {
            $avatar->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('avatar_create') && Gate::denies('avatar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Avatar();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
