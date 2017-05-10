@section('content')
  @parent
  <div class="table-controls" style="display: none; float: left">
    <label style="font-weight: normal">
      {{ $presenter->renderTakeSelector(isset($total) ? $total : null) }} 取得
      &nbsp;
    </label>
  </div>
@endsection

@section('script')
  @parent
  <script>
    $(function () {
      $('select[name=take]').on('change', function (e) {
        var value = $(this).val();
        var args = {};
        if (location.search) {
          var search = location.search.substr(1);
          $.each(search.split('&'), function () {
            var pair = this.split('=');
            args[pair[0]] = pair[1];
          });
          args['take'] = value;
          location.search = $.param(args);
        } else {
          location.search = 'take='+value;
        }
      });
    });
  </script>
@endsection
