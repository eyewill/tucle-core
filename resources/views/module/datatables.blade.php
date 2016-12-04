@section('stylesheet')
  @parent
  <link rel="stylesheet" href="/assets/datatables/css/dataTables.bootstrap.min.css">
@endsection

@section('script')
  @parent
  <script src="/assets/datatables/js/jquery.dataTables.min.js"></script>
  <script src="/assets/datatables/js/dataTables.bootstrap.min.js"></script>
  <script>
    $(function(){
      $.extend($.fn.dataTable.defaults, {
        stateSave: true,
        language: {
          url: "http://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Japanese.json"
        }
      });
      $('[data-provider=datatables]').each(function () {
        $(this).DataTable();
        $(this).attr('width', '100%');
      });
      $('[data-action-clear]').on('click', function (e) {
        e.preventDefault();
        $($(this).data('action-clear')).dataTable().api().state.clear();
        window.location.reload();
      });
    });
  </script>
@endsection
