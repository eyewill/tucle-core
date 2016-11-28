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

  });
</script>
