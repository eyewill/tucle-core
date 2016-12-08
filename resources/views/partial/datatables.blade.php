<script>
  $(function(){
    $.extend($.fn.dataTable.defaults, {
      order: [],
      columnDefs: [
        { className: 'align-middle', targets: '_all' },
        { type: "html", targets: "_all" }
      ],
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

