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
        location.search = 'take='+value;
      });
    });
  </script>
@endsection
