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
              if (typeof(value[name]) == 'undefined') {
                value[name] = {};
              }
              value[name][$(this).val()] = $(this).prop('checked');
            } else if (type == 'radio') {
              if ($(this).is(':checked')) {
                value[name] = $(this).val();
              }
            } else {
              value[name] = $(this).val();
            }
          });
        },
        stateLoadParams: function (settings, data) {
          $('[data-filter]').each(function() {
            var filter = $(this);
            var type = filter.prop('type');
            if (this.tagName == 'SELECT') {
              type = 'select';
            }
            var name = filter.prop('name');
            $.each(data, function (index, value) {
              if (index == name) {
                if (type == 'checkbox') {
                  filter.prop('checked', value[filter.val()]);
                } else if (type == 'radio') {
                  filter.val([value]);
                } else {
                  filter.val(value);
                }
              }
            });
          });
          return true;
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
            var filterChangeHandler = function (e) {
              $(this).off('change', filterChangeHandler);
              var label = trigger.data('label');
              var value = false;
              if (type == 'checkbox') {
                var checkboxes = $('[name="'+$(this).prop('name')+'"]:checked');
                var labels = [];
                var values = [];
                for(var i=0; i< checkboxes.length; i++) {
                  labels.push($(checkboxes[i]).closest('label').text());
                  values.push($(checkboxes[i]).val());
                }
                if (checkboxes.length > 0) {
                  label = labels.join(',');
                  value = values.join(',');
                }
              } else if (type == 'radio') {
                modal.modal('hide');
                value = $('[name="'+$(this).prop('name')+'"]:checked').val();
                if (value)
                  label = $('[name="'+$(this).prop('name')+'"]:checked').closest('label').text();
              } else {
                modal.modal('hide');
                value = $(this).val();
                if (value)
                  label = $(this).find(':selected').text();
              }
              if (value) {
                trigger.removeClass('filter-none').addClass('btn-primary');
              } else {
                trigger.addClass('filter-none').removeClass('btn-primary');
              }
              trigger.text(label);

              var isFiltering = false;
              $('[data-filter]').each(function() {
                var type = $(this).prop('type');
                if (this.tagName == 'SELECT') {
                  type = 'select';
                }
                if (type == 'checkbox') {
                  var checkboxes = $('[name="'+$(this).prop('name')+'"]:checked');
                  value = (checkboxes.length > 0);
                } else if (type == 'radio') {
                  value = $(this).filter(':checked');
                } else {
                  value = $(this).val();
                }
                if (value) isFiltering = true;
              });
               $('#filter_clear').prop('disabled', !isFiltering);

              $(this).on('change', filterChangeHandler);

              dt.draw();
            };

            $(this).on('change', filterChangeHandler);
          });

          $('#filter_clear').on('click', function () {
            $('[data-filter]').each(function() {
              var type = $(this).prop('type');
              if (this.tagName == 'SELECT') {
                type = 'select';
              }
              if (type == 'checkbox') {
                $(this).prop('checked', false);
              } else if (type == 'radio') {
                $(this).prop('checked', false);
              } else {
                $(this).val('');
              }
              $(this).trigger('change');
            });
          });


          $('[data-filter]').each(function() {
            var filter = $(this);
            var col = filter.data('filter');
            var type = filter.prop('type');
            var mode = filter.data('mode');
            if (filter.get(0).tagName == 'SELECT') {
              type = 'select';
            }
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
              if (type == 'checkbox') {
                var checkboxes = $('[name="'+filter.prop('name')+'"]:checked');
                if (checkboxes.length > 0) {
                  var matchedSomeone = false;
                  for (var i = 0; i < checkboxes.length; i++) {
                    if (mode == 'like') {
                      if (data[col].indexOf(filter.val()) !== -1)
                        matchedSomeone = true;
                    } else {
                      if (data[col] == filter.val())
                        matchedSomeone = true;
                    }
                  }
                  if (!matchedSomeone)
                    return false;
                }
              } else if (type == 'radio') {
                if (filter.is(':checked') && filter.val() != '')
                {
                  if (mode == 'like') {
                    if (data[col].indexOf(filter.val()) === -1)
                      return false;
                  } else {
                    if (data[col] != filter.val())
                      return false;
                  }
                }
              } else {
                if (filter.val()) {
                  if (mode == 'like') {
                    if (data[col].indexOf(filter.val()) === -1)
                      return false;
                  } else {
                    if (data[col] != filter.val())
                      return false;
                  }
                }
              }
              return true;
            });


//            $(this).trigger('change');
          });
          DataTablesFilter.filter();
          dt.draw();

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

          $(DataTablesFactory.table).show();
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

    var DataTablesFilter = {
      filters: '[data-filter]',
      filter: function (filter) {

        $(DataTablesFilter.filters).each(function() {

          var filterName = $(this).data('filter');

          if (typeof filter == 'undefined' || filter == filterName) {

            var type = $(this).prop('type');
            if (this.tagName == 'SELECT') {
              type = 'select';
            }
            var trigger = $($(this).data('trigger'));
            var modal = $(this).closest('.modal');

            var label = trigger.data('label');
            var value = false;
            if (type == 'checkbox') {
              var checkboxes = $('[name="'+$(this).prop('name')+'"]:checked');
              var labels = [];
              var values = [];
              for(var i=0; i< checkboxes.length; i++) {
                labels.push($(checkboxes[i]).closest('label').text());
                values.push($(checkboxes[i]).val());
              }
              if (checkboxes.length > 0) {
                label = labels.join(',');
                value = values.join(',');
              }
            } else if (type == 'radio') {
              modal.modal('hide');
              value = $('[name="'+$(this).prop('name')+'"]:checked').val();
              if (value)
                label = $('[name="'+$(this).prop('name')+'"]:checked').closest('label').text();
            } else {
              modal.modal('hide');
              value = $(this).val();
              if (value)
                label = $(this).find(':selected').text();
            }
            if (value) {
              trigger.removeClass('filter-none').addClass('btn-primary');
            } else {
              trigger.addClass('filter-none').removeClass('btn-primary');
            }
            trigger.text(label);
          }

        });

        var isFiltering = false;
        $('[data-filter]').each(function() {
          var type = $(this).prop('type');
          if (this.tagName == 'SELECT') {
            type = 'select';
          }
          if (type == 'checkbox') {
            var checkboxes = $('[name="'+$(this).prop('name')+'"]:checked');
            value = (checkboxes.length > 0);
          } else if (type == 'radio') {
            value = $(this).filter(':checked');
          } else {
            value = $(this).val();
          }
          if (value) isFiltering = true;
        });
        $('#filter_clear').prop('disabled', !isFiltering);
      }
    };
  </script>

  @include($presenter->view('partial.datatables.make'))

@endsection
