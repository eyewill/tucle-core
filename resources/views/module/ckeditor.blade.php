@section('script')
  @parent
  <script src="/assets/ckeditor/ckeditor.js"></script>
  <script>
    $('textarea[data-provider=ckeditor]').each(function () {
      if (Boolean($(this).data('wysiwyg'))) {
        CKEDITOR.replace($(this).prop('name'));
      }
    });
  </script>
@endsection