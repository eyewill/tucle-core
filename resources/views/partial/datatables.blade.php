<script>
  $(function(){
    $('#entries').DataTable({
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

