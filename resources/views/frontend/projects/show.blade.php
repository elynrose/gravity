@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.project.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.projects.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.project.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $project->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.project.fields.prompt') }}
                                    </th>
                                    <td>
                                        {{ $project->prompt }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.project.fields.script') }}
                                    </th>
                                    <td>
                                        {{ $project->script }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.project.fields.status') }}
                                    </th>
                                    <td>
                                        {{ $project->status }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.project.fields.privacy') }}
                                    </th>
                                    <td>
                                        {{ App\Models\Project::PRIVACY_RADIO[$project->privacy] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.project.fields.user') }}
                                    </th>
                                    <td>
                                        {{ $project->user->name ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.projects.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection