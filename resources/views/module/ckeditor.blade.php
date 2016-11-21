@section('script')
  @parent
  <script src="/assets/ckeditor/ckeditor.js"></script>
  <script>
    $('textarea').each(function () {
      CKEDITOR.replace($(this).prop('name'));
    });
  </script>
@endsection