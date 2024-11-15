@extends('layouts.admin')
@section('content')
@can('avatar_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.avatars.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.avatar.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.avatar.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Avatar">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.avatar.fields.project') }}
                        </th>
                        <th>
                            {{ trans('cruds.avatar.fields.prompt') }}
                        </th>
                        <th>
                            {{ trans('cruds.avatar.fields.avatar_url') }}
                        </th>
                        <th>
                            {{ trans('cruds.avatar.fields.avatar') }}
                        </th>
                        <th>
                            {{ trans('cruds.avatar.fields.completed') }}
                        </th>
                        <th>
                            {{ trans('cruds.avatar.fields.token') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($avatars as $key => $avatar)
                        <tr data-entry-id="{{ $avatar->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $avatar->project->name ?? '' }}
                            </td>
                            <td>
                                {{ $avatar->prompt ?? '' }}
                            </td>
                            <td>
                                {{ $avatar->avatar_url ?? '' }}
                            </td>
                            <td>
                                {{ $avatar->avatar ?? '' }}
                            </td>
                            <td>
                                <span style="display:none">{{ $avatar->completed ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $avatar->completed ? 'checked' : '' }}>
                            </td>
                            <td>
                                {{ $avatar->token ?? '' }}
                            </td>
                            <td>
                                @can('avatar_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.avatars.show', $avatar->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('avatar_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.avatars.edit', $avatar->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('avatar_delete')
                                    <form action="{{ route('admin.avatars.destroy', $avatar->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('avatar_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.avatars.massDestroy') }}",
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
  let table = $('.datatable-Avatar:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection