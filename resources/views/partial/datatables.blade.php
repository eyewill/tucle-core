<script>
  $(function(){
    $.extend($.fn.dataTable.defaults, {
      order: [],
      columnDefs: [
        { className: 'align-middle', targets: '_all' },
        { width: '1px', targets: 0 },
        { orderable: false, targets: 0 },
        { searchable: false, targets: 0 },
        { checkboxes: { selectRow: true, selectAllPages: false }, targets: 0 },
        { type: "html", targets: "_all" }
      ],
      select: {
        style: 'multi',
        selector: 'td:first-child'
      },
      autoWidth: false,
      stateSave: true,
      language: {
        url: "/assets/datatables/i18n/Japanese.json"
      }
    });
    $('[data-action-clear]').on('click', function (e) {
      e.preventDefault();
      $($(this).data('action-clear')).dataTable().api().state.clear();
      window.location.reload();
    });
  });
</script>

