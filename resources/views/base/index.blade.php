@extends('tucle::base.layout')

@include('tucle::module.datatables')

@section('content')
<div class="container">

  @section('breadcrumbs')
  {{ $presenter->renderBreadCrumbs(['label' => '一覧']) }}
  @show

  @section('page-header')
  <div class="page-header">
    <div class="row">
      <div class="col-md-6">
        <h1 class="form-title">{{ $presenter->getPageTitle() }}</h1>
      </div>
      <div class="col-md-6">
        @section('actions')
          @include($presenter->view('actions.index'))
        @show
      </div>
    </div>
  </div>
  @show

  @section('entries')
    <div class="table-actions" style="display:none">
      <div class="row">
        <div class="col-sm-12">
          <div id="table-actions" class="btn-actions">
            <button class="btn btn-default" data-table-action="clearall" disabled>
              <span class="fa fa-square-o"></span>
              全クリア
            </button>
            <button class="btn btn-primary" data-table-action="put" data-url="{{ $presenter->route('batch') }}" data-attributes='{"published_at":"now","terminated_at":""}' disabled>
              <span class="fa fa-check"></span>
              公開
            </button>
            <button class="btn btn-warning" data-table-action="put" data-url="{{ $presenter->route('batch') }}" data-attributes='{"terminated_at":"-1 minute"}' disabled>
              <span class="fa fa-ban"></span>
              公開終了
            </button>
            <button class="btn btn-danger" data-table-action="delete" data-url="{{ $presenter->route('batch') }}" disabled>
              <span class="fa fa-trash-o"></span>
              削除
            </button>
          </div>
        </div>
      </div>
    </div>
    @include('tucle::partial.entries', [
      'entries' => $entries,
      'presenter' => $presenter,
    ])
  @show

</div>
@endsection

@section('script')
  @parent

  @include('tucle::partial.datatables')

  @section('datatables')
<script>
  $(function () {
    $('#entries').DataTable({
      columnDefs: [
        { className: 'align-middle', targets: '_all' },
        { type: "html", targets: "_all" },
        { width: '1px', targets: 0 },
        { orderable: false, targets: 0 },
        { searchable: false, targets: 0 },
        { checkboxes: { selectRow: true, selectAllPages: false }, targets: 0 },
      ],
      select: {
        style: 'multi',
        selector: false
      },
      initComplete: function(settings, json) {
        var dt = this.api();
        this.api().on('select', function (e, dt, type, indexes) {
          toggle(dt);
        }).on('deselect', function (e, dt, type, indexes) {
          toggle(dt);
        });
        $('#entries_wrapper .row:eq(0)').after($('.table-actions').show());

        $('[data-table-action=delete]').on('click', function (e) {
          var url = $(this).data('url');
          var rows = selectRows(dt);
          if (confirm(rows.count()+'件のレコードを削除します。よろしいですか？')) {
            var data = [];

            selectRows(dt).data().each(function (row) {
              data.push({
                type: 'delete',
                id: row[0]
              })
            });
            $.batchRequest(url, data);
          }
        });

        $('[data-table-action=put]').on('click', function (e) {
          var url = $(this).data('url');
          var attributes = $(this).data('attributes');
          var rows = selectRows(dt);
          if (confirm(rows.count()+'件のレコードを更新します。よろしいですか？')) {
            var data = [];

            selectRows(dt).data().each(function (row) {
              data.push({
                type: 'put',
                id: row[0],
                attributes: attributes
              })
            });
            $.batchRequest(url, data);
          }
        });

        $('[data-table-action=clearall]').on('click', function (e) {
          selectRows(dt).deselect();
        });
      }
    });
    var toggle = function (dt) {
      var selected = (selectRows(dt).count() > 0);
      $('#table-actions .btn').prop('disabled', !selected);
    };
    var selectRows = function (dt) {
      return dt.rows({ selected: true });
    };
  });
</script>
  @show

@endsection
