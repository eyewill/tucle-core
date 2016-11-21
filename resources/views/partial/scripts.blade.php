<script>
  $(function(){

    /**
     * AJAX
     */
    $('[data-action]').on('click', function (e) {
      var action = $(this).data('action');

      e.preventDefault();

      var form = $('<form action="'+$(this).data('url')+'" method="POST">');
      form.append($('<input type="hidden" name="_token">').val('{{ csrf_token() }}'));
      if (action == 'update') {
        form.append($('<input type="hidden" name="_method" value="PATCH">'));
        form.append($('<input type="hidden">').prop({
          name: $(this).data('field'),
          value: $(this).data('value')
        }));
      } else if (action == 'destroy') {
        form.append($('<input type="hidden" name="_method" value="DELETE">'));
      } else if (action == 'copy') {
        //
      }

      $('body').append(form);
      form.submit();

    }).removeClass('disabled').prop('disabled', false);


    /**
     * 通知
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
        placement: {
          from: 'top',
          align: 'center'
        },
        offset: {
          y: 96
        },
        timer: 1000,
        z_index: 0
      });
      @endif
    @endforeach

  });
</script>
