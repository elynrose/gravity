@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.video.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.videos.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="project_id">{{ trans('cruds.video.fields.project') }}</label>
                            <select class="form-control select2" name="project_id" id="project_id" required>
                                @foreach($projects as $id => $entry)
                                    <option value="{{ $id }}" {{ old('project_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('project'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('project') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.video.fields.project_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="video_code">{{ trans('cruds.video.fields.video_code') }}</label>
                            <input class="form-control" type="text" name="video_code" id="video_code" value="{{ old('video_code', '') }}">
                            @if($errors->has('video_code'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('video_code') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.video.fields.video_code_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="video_url">{{ trans('cruds.video.fields.video_url') }}</label>
                            <textarea class="form-control" name="video_url" id="video_url">{{ old('video_url') }}</textarea>
                            @if($errors->has('video_url'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('video_url') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.video.fields.video_url_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <div>
                                <input type="hidden" name="saved" value="0">
                                <input type="checkbox" name="saved" id="saved" value="1" {{ old('saved', 0) == 1 ? 'checked' : '' }}>
                                <label for="saved">{{ trans('cruds.video.fields.saved') }}</label>
                            </div>
                            @if($errors->has('saved'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('saved') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.video.fields.saved_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <div>
                                <input type="hidden" name="completed" value="0">
                                <input type="checkbox" name="completed" id="completed" value="1" {{ old('completed', 0) == 1 ? 'checked' : '' }}>
                                <label for="completed">{{ trans('cruds.video.fields.completed') }}</label>
                            </div>
                            @if($errors->has('completed'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('completed') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.video.fields.completed_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="minutes">{{ trans('cruds.video.fields.minutes') }}</label>
                            <input class="form-control" type="text" name="minutes" id="minutes" value="{{ old('minutes', '') }}">
                            @if($errors->has('minutes'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('minutes') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.video.fields.minutes_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection