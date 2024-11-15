@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.project.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.projects.update", [$project->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.project.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $project->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.project.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="prompt">{{ trans('cruds.project.fields.prompt') }}</label>
                <textarea class="form-control {{ $errors->has('prompt') ? 'is-invalid' : '' }}" name="prompt" id="prompt" required>{{ old('prompt', $project->prompt) }}</textarea>
                @if($errors->has('prompt'))
                    <div class="invalid-feedback">
                        {{ $errors->first('prompt') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.project.fields.prompt_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="script">{{ trans('cruds.project.fields.script') }}</label>
                <textarea class="form-control {{ $errors->has('script') ? 'is-invalid' : '' }}" name="script" id="script" required>{{ old('script', $project->script) }}</textarea>
                @if($errors->has('script'))
                    <div class="invalid-feedback">
                        {{ $errors->first('script') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.project.fields.script_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="status">{{ trans('cruds.project.fields.status') }}</label>
                <input class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" type="text" name="status" id="status" value="{{ old('status', $project->status) }}" required>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.project.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.project.fields.privacy') }}</label>
                @foreach(App\Models\Project::PRIVACY_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('privacy') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="privacy_{{ $key }}" name="privacy" value="{{ $key }}" {{ old('privacy', $project->privacy) === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="privacy_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('privacy'))
                    <div class="invalid-feedback">
                        {{ $errors->first('privacy') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.project.fields.privacy_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="user_id">{{ trans('cruds.project.fields.user') }}</label>
                <select class="form-control select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id" required>
                    @foreach($users as $id => $entry)
                        <option value="{{ $id }}" {{ (old('user_id') ? old('user_id') : $project->user->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user'))
                    <div class="invalid-feedback">
                        {{ $errors->first('user') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.project.fields.user_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection