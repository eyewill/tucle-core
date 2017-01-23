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
          {
            checkboxes: { selectRow: true, selectAllPages: false },
            orderable: false,
            searchable: false,
            targets: 0
          }
        ],
        select: {
          style: 'multi',
          selector: false
        },
        stateSaveParams: function (settings, value) {
          $('[data-filter]').each(function() {
            var type = $(this).prop('type');
            if (this.tagName == 'SELECT') {
              type = 'select';
            }
            var name = $(this).prop('name');
            if (type == 'checkbox') {
              value[name] = $(this).prop('checked');
            } else if (type == 'radio') {
              value[name] = $(this).filter(':checked').val();
            } else {
              value[name] = $(this).val();
            }
          });
        },
        stateLoadParams: function (settings, data) {
          $('[data-filter]').each(function() {
            var type = $(this).prop('type');
            if (this.tagName == 'SELECT') {
              type = 'select';
            }
            var name = $(this).prop('name');
            $.each(data, function (index, value) {
              if (index == name) {
                if (type == 'checkbox') {
                  $(this).prop('checked', value);
                } else if (type == 'radio') {
                  $(this).filter(':checked').val(value);
                } else {
                  $(this).val(value);
                }
              }
            });
          });
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

          $('#entries_wrapper .row:eq(0)').after($('.table-filters').show());

          // カスタムフィルタ
          $('[data-filter]').each(function() {
            var type = $(this).prop('type');
            if (this.tagName == 'SELECT') {
              type = 'select';
            }
            var trigger = $($(this).data('trigger'));
            var modal = $(this).closest('.modal');
            $(this).on('change', function (e) {
              var label = trigger.data('label');
              modal.modal('hide');
              var value = false;
              if (type == 'checkbox') {
                value = $(this).prop('checked');
              } else if (type == 'radio') {
                value = $(this).filter(':checked').val();
              } else {
                value = $(this).val();
                if (value) label = value;
              }
              if (value) {
                trigger.removeClass('filter-none').addClass('btn-primary');
              } else {
                trigger.addClass('filter-none').removeClass('btn-primary');
              }
              trigger.text(label);

              dt.draw();
            });
          });

          $('[data-filter]').each(function() {
            var filter = $(this);
            var col = filter.data('filter');
            var type = filter.prop('type');
            if (filter.get(0).tagName == 'SELECT') {
              type = 'select';
            }
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
              if (type == 'checkbox') {
                if (filter.prop('checked') && data[col] != filter.val())
                  return false;
              } else if (type == 'radio') {
                if (filter.filter(';checked').length > 0 && data[col] != filter.filter(':checked').val())
                  return false;
              } else {
                if (filter.val() && data[col] != filter.val())
                  return false;
              }
              return true;
            });
          });

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
