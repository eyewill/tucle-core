@section('stylesheet')
  @parent
  <link rel="stylesheet" href="/assets/datatables/css/dataTables.bootstrap.min.css">
  {{--<link rel="stylesheet" href="/assets/datatables/css/select.bootstrap.css">--}}
  <link rel="stylesheet" href="/assets/datatables/css/dataTables.checkboxes.css">
  <style>
  </style>
@endsection

@section('script')
  @parent
  <script src="/assets/datatables/js/jquery.dataTables.min.js"></script>
  <script src="/assets/datatables/js/dataTables.bootstrap.min.js"></script>
  <script src="/assets/datatables/js/dataTables.select.min.js"></script>
  <script src="/assets/datatables/js/dataTables.checkboxes.min.js"></script>
  <script>
    $(function(){
      $.extend($.fn.dataTable.defaults, {
        order: [],
        columnDefs: [
          { className: 'align-middle', targets: '_all' },
          { width: '1px', targets: 0 },
          { orderable: false, targets: 0 },
          { searchable: false, targets: 0 },
          { checkboxes: { selectRow: true }, targets: 0 },
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
      $('[data-provider=datatables]').each(function () {
        $(this).DataTable();
      });
      $('[data-action-clear]').on('click', function (e) {
        e.preventDefault();
        $($(this).data('action-clear')).dataTable().api().state.clear();
        window.location.reload();
      });
    });
  </script>
@endsection
