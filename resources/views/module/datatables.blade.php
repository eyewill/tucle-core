@section('stylesheet')
  @parent
  <link rel="stylesheet" href="/assets/datatables/css/datatables.min.css">
  <link rel="stylesheet" href="/assets/datatables/css/dataTables.checkboxes.css">
  <style>
  </style>
@endsection

@section('script')
  @parent
  <script src="/assets/datatables/js/datatables.min.js"></script>
  <script src="/assets/datatables/js/dataTables.select.min.js"></script>
  <script src="/assets/datatables/js/dataTables.checkboxes.min.js"></script>

  <script>
    $.extend($.fn.dataTable.defaults, {
      order: [],
      autoWidth: false,
      stateSave: true,
      language: {
        url: "/assets/datatables/i18n/Japanese.json",
        select: {
          rows: {
            _: '%d 件選択中',
            0: ''
          }
        }
      }
    });
    $('[data-action-clear]').on('click', function (e) {
      e.preventDefault();
      $($(this).data('action-clear')).dataTable().api().state.clear();
      window.location.reload();
    });

    var DataTablesFactory = {
      table: '#entries',
      options: {
        columnDefs: [
          { className: 'align-middle', targets: '_all' },
          { type: "html", targets: "_all" },
          { width: '1px', targets: 0 },
          { orderable: false, targets: 0 },
          { searchable: false, targets: 0 },
          { checkboxes: { selectRow: true, selectAllPages: false }, targets: 0 }
        ],
        select: {
          style: 'multi',
          selector: false
        },
        initComplete: function(settings, json) {
          var dt = this.api();
          this.api().on('select', function (e, dt, type, indexes) {
            DataTablesFactory.toggle(dt);
          }).on('deselect', function (e, dt, type, indexes) {
            DataTablesFactory.toggle(dt);
          });
          $('#entries_wrapper .row:eq(0)').after($('.table-actions').show());
          $('#entries_wrapper div[id$=_filter] input').prop('class', 'form-control input-md');

          $('[data-table-action=delete]').on('click', function (e) {

            var url = $(this).data('url');
            var rows = DataTablesFactory.selectRows(dt);
            if (confirm(rows.count()+'件のレコードを削除します。よろしいですか？')) {
              var data = [];

              DataTablesFactory.selectRows(dt).data().each(function (row) {
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
            var rows = DataTablesFactory.selectRows(dt);
            if (confirm(rows.count()+'件のレコードを更新します。よろしいですか？')) {
              var data = [];

              DataTablesFactory.selectRows(dt).data().each(function (row) {
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
            DataTablesFactory.selectRows(dt).deselect();
          });
        }
      },
      toggle: function (dt) {
        var selected = (DataTablesFactory.selectRows(dt).count() > 0);
        $('.table-actions .btn-actions .btn').prop('disabled', !selected);
      },
      selectRows: function (dt) {
        return dt.rows({ selected: true });
      },
      make: function () {
        return $(this.table).DataTable(this.options);
      }
    };
  </script>

  @include($presenter->view('partial.datatables.make'))

@endsection
