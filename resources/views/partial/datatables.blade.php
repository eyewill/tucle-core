<script>
  $(function(){
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
  });
</script>

