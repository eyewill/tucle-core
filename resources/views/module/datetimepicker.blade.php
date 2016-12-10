@section('stylesheet')
  @parent
  <link rel="stylesheet" href="/assets/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">
@endsection

@section('script')
  @parent
  <script src="/assets/moment/js/moment-with-locales.min.js"></script>
  <script src="/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>

  <script>
    $('[data-provider=datetimepicker]').each(function () {
      $(this).datetimepicker({
        locale: 'ja'
      });
    });
  </script>
@endsection
