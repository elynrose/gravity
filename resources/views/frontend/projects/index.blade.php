@extends('layouts.frontend')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('project_create')
                <div class="mb-4">
                    <a class="btn btn-success" href="{{ route('frontend.projects.create') }}">
                        {{ trans('global.add') }} {{ trans('cruds.project.title_singular') }}
                    </a>
                </div>
            @endcan

            <div class="row">
                @foreach($projects as $project)
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-header">
                                {{ $project->name ?? 'Untitled Project' }}
                            </div>
                            <div class="card-body">
                                <div class="display_{{ $project->id }}">
                                    @if($project->avatar && $project->avatar->avatar)
                                        <img src="{{ $project->avatar->avatar->getUrl('thumb') }}" alt="Avatar Thumbnail" class="img-fluid">
                                    @endif
                                </div>
                                <p class="mt-2 badge badge-primary">
                                    <span id="status_{{ $project->id }}" rel="{{ $project->id }}" 
                                          class="@if($project->status !== 'ready') waiting @endif">
                                        @if($project->status == 'new')
                                            <i class="fas fa-spinner fa-spin"></i> New
                                        @elseif($project->status == 'avatar')
                                            <i class="fas fa-user"></i> Working...
                                        @elseif($project->status == 'audio')
                                            <i class="fas fa-bullhorn"></i> Creating Audio...
                                        @elseif($project->status == 'video')
                                            <i class="fas fa-video"></i> Creating Video...
                                        @elseif($project->status == 'ready')
                                            <i class="fas fa-check"></i> Completed
                                        @endif
                                    </span>
                                </p>
                                <p class="text-muted small"><strong>{{ trans('cruds.project.fields.created_at') }}:</strong> 
                                    {{ $project->created_at ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="card-footer text-right">
                                @can('project_show')
                                    <a href="{{ $project->video($project->id)->video_url }}" 
                                       id="download_{{ $project->id }}" 
                                       class="@if($project->status!=='ready') hide @endif btn btn-success btn-sm text-white @if($project->status !== 'ready') hide @endif">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                @endcan
                                @can('project_delete')
                                    <form action="{{ route('frontend.projects.destroy', $project->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('{{ trans('global.areYouSure') }}');" 
                                          style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
    function getStatus() {
        $('.waiting').each(function () {
            let id = $(this).attr('rel');
            let ajax_url = `/get-video/${id}`;

            $('.fa_' + id).addClass('fa-spin');
            $.ajax({
                url: ajax_url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { id: id },
                success: function (response) {
                    let obj = response;
                    if (obj.status === 'avatar') {
                        $('#status_' + id).html('<i class="fas fa-user"></i> Working...');
                    } else if (obj.status === 'audio') {
                        $('#status_' + id).html('<i class="fas fa-bullhorn"></i> Creating Audio...');
                    } else if (obj.status === 'video') {
                        $('#status_' + id).html('<i class="fas fa-video"></i> Creating Video...');
                    } else if (obj.status === 'ready') {
                        $('#download_' + id).removeClass('hide').attr('href', obj.url);
                        $('#status_' + id).html('<i class="fas fa-check"></i> Completed');
                    } else if (obj.status === 'new') {
                        $('#status_' + id).html('<i class="fas fa-spinner fa-spin"></i> New');
                    }
                },
                error: function (response) {
                    console.error(response);
                }
            });
        });
    }
    setInterval(getStatus, 1000);
</script>
@endsection
