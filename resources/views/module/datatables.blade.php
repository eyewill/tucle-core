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
    $(function () {
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

  @include($presenter->view('datatables.init'))

@endsection
