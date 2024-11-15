@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.video.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.videos.update", [$video->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="project_id">{{ trans('cruds.video.fields.project') }}</label>
                <select class="form-control select2 {{ $errors->has('project') ? 'is-invalid' : '' }}" name="project_id" id="project_id" required>
                    @foreach($projects as $id => $entry)
                        <option value="{{ $id }}" {{ (old('project_id') ? old('project_id') : $video->project->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
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
                <label for="video">{{ trans('cruds.video.fields.video') }}</label>
                <div class="needsclick dropzone {{ $errors->has('video') ? 'is-invalid' : '' }}" id="video-dropzone">
                </div>
                @if($errors->has('video'))
                    <div class="invalid-feedback">
                        {{ $errors->first('video') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.video.fields.video_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="video_code">{{ trans('cruds.video.fields.video_code') }}</label>
                <input class="form-control {{ $errors->has('video_code') ? 'is-invalid' : '' }}" type="text" name="video_code" id="video_code" value="{{ old('video_code', $video->video_code) }}">
                @if($errors->has('video_code'))
                    <div class="invalid-feedback">
                        {{ $errors->first('video_code') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.video.fields.video_code_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="video_url">{{ trans('cruds.video.fields.video_url') }}</label>
                <textarea class="form-control {{ $errors->has('video_url') ? 'is-invalid' : '' }}" name="video_url" id="video_url">{{ old('video_url', $video->video_url) }}</textarea>
                @if($errors->has('video_url'))
                    <div class="invalid-feedback">
                        {{ $errors->first('video_url') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.video.fields.video_url_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('saved') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="saved" value="0">
                    <input class="form-check-input" type="checkbox" name="saved" id="saved" value="1" {{ $video->saved || old('saved', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="saved">{{ trans('cruds.video.fields.saved') }}</label>
                </div>
                @if($errors->has('saved'))
                    <div class="invalid-feedback">
                        {{ $errors->first('saved') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.video.fields.saved_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('completed') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="completed" value="0">
                    <input class="form-check-input" type="checkbox" name="completed" id="completed" value="1" {{ $video->completed || old('completed', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="completed">{{ trans('cruds.video.fields.completed') }}</label>
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
                <input class="form-control {{ $errors->has('minutes') ? 'is-invalid' : '' }}" type="text" name="minutes" id="minutes" value="{{ old('minutes', $video->minutes) }}">
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



@endsection

@section('scripts')
<script>
    Dropzone.options.videoDropzone = {
    url: '{{ route('admin.videos.storeMedia') }}',
    maxFilesize: 2, // MB
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2
    },
    success: function (file, response) {
      $('form').find('input[name="video"]').remove()
      $('form').append('<input type="hidden" name="video" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="video"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($video) && $video->video)
      var file = {!! json_encode($video->video) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="video" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>
@endsection