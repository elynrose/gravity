@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('audio_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.audios.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.audio.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.audio.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Audio">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.audio.fields.project') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.audio.fields.audio') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.audio.fields.audio_url') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.audio.fields.completed') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.audio.fields.token') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($audios as $key => $audio)
                                    <tr data-entry-id="{{ $audio->id }}">
                                        <td>
                                            {{ $audio->project->name ?? '' }}
                                        </td>
                                        <td>
                                            @if($audio->audio)
                                                <a href="{{ $audio->audio->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $audio->audio_url ?? '' }}
                                        </td>
                                        <td>
                                            <span style="display:none">{{ $audio->completed ?? '' }}</span>
                                            <input type="checkbox" disabled="disabled" {{ $audio->completed ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            {{ $audio->token ?? '' }}
                                        </td>
                                        <td>
                                            @can('audio_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.audios.show', $audio->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('audio_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.audios.edit', $audio->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('audio_delete')
                                                <form action="{{ route('frontend.audios.destroy', $audio->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                                </form>
                                            @endcan

                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('audio_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.audios.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-Audio:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection