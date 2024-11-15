@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.avatar.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.avatars.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="project_id">{{ trans('cruds.avatar.fields.project') }}</label>
                <select class="form-control select2 {{ $errors->has('project') ? 'is-invalid' : '' }}" name="project_id" id="project_id" required>
                    @foreach($projects as $id => $entry)
                        <option value="{{ $id }}" {{ old('project_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('project'))
                    <div class="invalid-feedback">
                        {{ $errors->first('project') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.avatar.fields.project_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="prompt">{{ trans('cruds.avatar.fields.prompt') }}</label>
                <textarea class="form-control {{ $errors->has('prompt') ? 'is-invalid' : '' }}" name="prompt" id="prompt">{{ old('prompt') }}</textarea>
                @if($errors->has('prompt'))
                    <div class="invalid-feedback">
                        {{ $errors->first('prompt') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.avatar.fields.prompt_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="avatar_url">{{ trans('cruds.avatar.fields.avatar_url') }}</label>
                <input class="form-control {{ $errors->has('avatar_url') ? 'is-invalid' : '' }}" type="text" name="avatar_url" id="avatar_url" value="{{ old('avatar_url', '') }}">
                @if($errors->has('avatar_url'))
                    <div class="invalid-feedback">
                        {{ $errors->first('avatar_url') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.avatar.fields.avatar_url_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="avatar">{{ trans('cruds.avatar.fields.avatar') }}</label>
                <input class="form-control {{ $errors->has('avatar') ? 'is-invalid' : '' }}" type="text" name="avatar" id="avatar" value="{{ old('avatar', '') }}">
                @if($errors->has('avatar'))
                    <div class="invalid-feedback">
                        {{ $errors->first('avatar') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.avatar.fields.avatar_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('completed') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="completed" value="0">
                    <input class="form-check-input" type="checkbox" name="completed" id="completed" value="1" {{ old('completed', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="completed">{{ trans('cruds.avatar.fields.completed') }}</label>
                </div>
                @if($errors->has('completed'))
                    <div class="invalid-feedback">
                        {{ $errors->first('completed') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.avatar.fields.completed_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="token">{{ trans('cruds.avatar.fields.token') }}</label>
                <input class="form-control {{ $errors->has('token') ? 'is-invalid' : '' }}" type="number" name="token" id="token" value="{{ old('token', '') }}" step="1">
                @if($errors->has('token'))
                    <div class="invalid-feedback">
                        {{ $errors->first('token') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.avatar.fields.token_helper') }}</span>
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