@section('script')
  @parent
  <script src="/assets/js/bootstrap-notify/bootstrap-notify.min.js"></script>
  <script>
    $.notifyDefaults({
      type: 'success',
      showProgressbar: false,
      placement: {
        from: "bottom",
        align: "center"
      },
      animate: {
        enter: 'animated fadeInUp',
        exit: 'animated fadeOutDown'
      },
      offset: {
        x: 0,
        y: 60
      }
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
      type: notifications['{{ $name }}'].type
    });
    @endif
    @endforeach

  </script>
@endsection
