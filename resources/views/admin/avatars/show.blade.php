@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.avatar.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.avatars.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.avatar.fields.project') }}
                        </th>
                        <td>
                            {{ $avatar->project->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.avatar.fields.prompt') }}
                        </th>
                        <td>
                            {{ $avatar->prompt }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.avatar.fields.avatar') }}
                        </th>
                        <td>
                            @if($avatar->avatar)
                                <a href="{{ $avatar->avatar->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.avatar.fields.avatar_url') }}
                        </th>
                        <td>
                            {{ $avatar->avatar_url }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.avatar.fields.completed') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $avatar->completed ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.avatar.fields.token') }}
                        </th>
                        <td>
                            {{ $avatar->token }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.avatars.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection