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
          DataTablesFilter.register(dt);

          DataTablesFilter.filter();

//          console.log('draw initialized.');

          $('[data-table-action=delete]').on('click', function (e) {

            var url = $(this).data('url');
            var model = $(this).data('model');
            var rows = DataTablesFactory.selectRows(dt);
            if (confirm(rows.count()+'件のレコードを削除します。よろしいですか？')) {
              var data = [];

              DataTablesFactory.selectRows(dt).data().each(function (row) {
                data.push({
                  type: 'delete',
                  id: row[0],
                  model: model
                })
              });
              $.batchRequest(url, data);
            }
          });

          $('[data-table-action=put]').on('click', function (e) {
            var url = $(this).data('url');
            var model = $(this).data('model');
            var attributes = $(this).data('attributes');
            var rows = DataTablesFactory.selectRows(dt);
            if (confirm(rows.count()+'件のレコードを更新します。よろしいですか？')) {
              var data = [];

              DataTablesFactory.selectRows(dt).data().each(function (row) {
                data.push({
                  type: 'put',
                  id: row[0],
                  model: model,
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
      dt: undefined,
      selector: '[data-filter]',
      filters: {},
      register: function (dt) {

        DataTablesFilter.dt = dt;

        var search = function(settings, data, dataIndex) {

          var matched = true;

          // radioとcheckboxは一つにまとめて処理
          $.each(DataTablesFilter.filters, function(name, obj) {

            if (obj.type == 'checkbox') {
              var checkboxes = $('[name="'+name+'"]:checked');
              if (checkboxes.length > 0) {
                var matchedSomeone = false;
                for (var i = 0; i < checkboxes.length; i++) {
                  var checkedValue = checkboxes.eq(i).val();
                  if (obj.mode == 'like') { // Like検索
                    if (data[obj.number].indexOf(checkedValue) !== -1)
                      matchedSomeone = true;
                  } else { // 完全一致検索
                    if (data[obj.number] == checkedValue)
                      matchedSomeone = true;
                  }
                }
                if (!matchedSomeone) {
                  matched = false;
                  return false;
                }
              }
            } else if (obj.type == 'radio') {
              var radio = $('[name="'+name+'"]:checked');
              if (radio.length > 0 && radio.val())
              {
                if (obj.mode == 'like') {
                  if (data[obj.number].indexOf(radio.val()) === -1) {
                    matched = false;
                    return false;
                  }
                } else {
                  if (data[obj.number] != radio.val()) {
                    matched = false;
                    return false;
                  }
                }
              }
            } else if (obj.type == 'select') {
              var select = $('[name="'+name+'"]');
              if (select.val()) {
                if (obj.mode == 'like') {
                  if (data[obj.number].indexOf(select.val()) === -1) {
                    matched = false;
                    return false;
                  }
                } else {
                  if (data[obj.number] != select.val()) {
                    matched = false;
                    return false;
                  }
                }
              }
            }
          });

          return matched;
        };

        $.fn.dataTable.ext.search.push(search);

        $('[data-filter]').on('change', DataTablesFilter.filter);
        $('#filter_clear').on('click', DataTablesFilter.clear);
      },
      filter: function () {

        // radioとcheckboxは一つにまとめて処理
        DataTablesFilter.filters = {};
        var isFiltering = false;
        $(DataTablesFilter.selector).each(function() {

          if (!(this.name in DataTablesFilter.filters)) {

            var obj = {
              type: this.tagName == 'SELECT' ? 'select' : $(this).prop('type'),
              number: $(this).data('filter'),
              trigger: $($(this).data('trigger')),
              modal: $(this).closest('.modal'),
              label: $($(this).data('trigger')).data('label'),
              value: false,
              mode: $(this).data('mode')
            };

            if (obj.type == 'checkbox') {
              var checkboxes = $('[name="'+this.name+'"]:checked');
              var labels = [];
              var values = [];
              if (checkboxes.length > 0) {
                for(var i=0; i< checkboxes.length; i++) {
                  labels.push($(checkboxes[i]).closest('label').text());
                  values.push($(checkboxes[i]).val());
                }
                obj.label = labels.join(',');
                obj.value = checkboxes.length;
              }
            } else if (obj.type == 'radio') {
              var radio = $('[name="'+this.name+'"]:checked');
              obj.value = radio.val();
              if (obj.value)
                obj.label = radio.closest('label').text();
              obj.modal.modal('hide');
            } else if (obj.type == 'select') {
              obj.value = $(this).val();
              if (obj.value)
                obj.label = $(this).find(':selected').text();
              obj.modal.modal('hide');
            }

            if (obj.value) {
              obj.trigger.removeClass('filter-none').addClass('btn-primary');
            } else {
              obj.trigger.addClass('filter-none').removeClass('btn-primary');
            }
            obj.trigger.text(obj.label);
            if (obj.value)
              isFiltering = true;

            DataTablesFilter.filters[this.name] = obj;
          }
        });

        $('#filter_clear').prop('disabled', !isFiltering);

        DataTablesFilter.dt.draw();
      },
      clear: function () {
        $.each(DataTablesFilter.filters, function(name, obj) {

          if (obj.type == 'checkbox') {
            $('[name="'+name+'"]').prop('checked', false);
          } else if (obj.type == 'radio') {
            $('[name="'+name+'"]').prop('checked', false);
          } else if (obj.type == 'select') {
            $('[name="'+name+'"]').val('');
          }
        });

        DataTablesFilter.filter();
      }
    };
  </script>

  @include($presenter->view('partial.datatables.make'))

@endsection
