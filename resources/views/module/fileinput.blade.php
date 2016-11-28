@section('stylesheet')
  @parent
  <link href="/assets/bootstrap-fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection

@section('script')
  @parent
  <script src="/assets/bootstrap-fileinput/js/fileinput.min.js"></script>
  <script src="/assets/bootstrap-fileinput/js/plugins/canvas-to-blob.min.js"></script>
  <script src="/assets/bootstrap-fileinput/js/locales/ja.js"></script>
@endsection