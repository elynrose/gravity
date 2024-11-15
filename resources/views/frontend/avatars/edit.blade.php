@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.avatar.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.avatars.update", [$avatar->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="project_id">{{ trans('cruds.avatar.fields.project') }}</label>
                            <select class="form-control select2" name="project_id" id="project_id" required>
                                @foreach($projects as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('project_id') ? old('project_id') : $avatar->project->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
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
                            <textarea class="form-control" name="prompt" id="prompt">{{ old('prompt', $avatar->prompt) }}</textarea>
                            @if($errors->has('prompt'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('prompt') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.avatar.fields.prompt_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="avatar">{{ trans('cruds.avatar.fields.avatar') }}</label>
                            <div class="needsclick dropzone" id="avatar-dropzone">
                            </div>
                            @if($errors->has('avatar'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('avatar') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.avatar.fields.avatar_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="avatar_url">{{ trans('cruds.avatar.fields.avatar_url') }}</label>
                            <input class="form-control" type="text" name="avatar_url" id="avatar_url" value="{{ old('avatar_url', $avatar->avatar_url) }}">
                            @if($errors->has('avatar_url'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('avatar_url') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.avatar.fields.avatar_url_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <div>
                                <input type="hidden" name="completed" value="0">
                                <input type="checkbox" name="completed" id="completed" value="1" {{ $avatar->completed || old('completed', 0) === 1 ? 'checked' : '' }}>
                                <label for="completed">{{ trans('cruds.avatar.fields.completed') }}</label>
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
                            <input class="form-control" type="number" name="token" id="token" value="{{ old('token', $avatar->token) }}" step="1">
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

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    Dropzone.options.avatarDropzone = {
    url: '{{ route('frontend.avatars.storeMedia') }}',
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
      $('form').find('input[name="avatar"]').remove()
      $('form').append('<input type="hidden" name="avatar" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="avatar"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($avatar) && $avatar->avatar)
      var file = {!! json_encode($avatar->avatar) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="avatar" value="' + file.file_name + '">')
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