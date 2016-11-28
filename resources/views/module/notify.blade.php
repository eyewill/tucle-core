@section('script')
  @parent
  <script src="/assets/js/bootstrap-notify/bootstrap-notify.min.js"></script>
  <script>
    $.notifyDefaults({
      type: 'success',
      placement: {
        from: 'top',
        align: 'center'
      },
      timer: 1000
    });

    /**
     * フラッシュメッセージ
     */
    var notifications = {
      success: {
        icon: 'fa fa-check',
        type: 'success'
      },
      error: {
        icon: 'fa fa-times',
        type: 'danger'
      },
      notice: {
        icon: 'fa fa-info',
        type: 'info'
      },
      warning: {
        icon: 'fa fa-exclamation-triangle',
        type: 'warning'
      }
    };

    @foreach (['success', 'error', 'notice', 'warning'] as $name)
      @if (session()->has($name))
      $.notify({
      icon: notifications['{{ $name }}'].icon,
      message: '{{ session()->get($name) }}'
    }, {
      type: notifications['{{ $name }}'].type,
      offset: {
        y: 96
      },
      z_index: 0
    });
    @endif
    @endforeach

  </script>
@endsection
