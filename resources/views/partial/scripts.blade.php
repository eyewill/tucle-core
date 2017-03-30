<script>
  $(function(){

    /**
     * AJAX
     */
    $('[data-action]').on('click', function (e) {
      var action = $(this).data('action');

      e.preventDefault();

      var form = $('<form action="'+$(this).data('url')+'" method="POST">');
      form.append($('<input type="hidden" name="_token" value="{{ csrf_token() }}">'));
      if (action == 'update') {
        form.append($('<input type="hidden" name="_method" value="PATCH">'));
        form.append($('<input type="hidden">').prop({
          name: $(this).data('field'),
          value: $(this).data('value')
        }));
      } else if (action == 'destroy') {
        if (!confirm('削除します。よろしいですか？')) {
          return;
        }
        form.append($('<input type="hidden" name="_method" value="DELETE">'));
      }

      $('body').append(form);
      form.submit();

    }).removeClass('disabled').prop('disabled', false);

    /**
     * jQuery拡張
     * 入力内容クリア機能追加
     */
    $.fn.clearable = function(callback, margin) {
      margin = margin || 0;
      var input = $(this);
      var top = input.position().top+9;
      var padding = Number(input.parent().css('padding-right').replace('px', ''));
      input.parent().css('position', 'relative');
      var right = input.parent().innerWidth()-input.outerWidth()-padding + margin + 9;
      $(this).after(
        $('<span class="btn-clear-input fa fa-times-circle"></span>')
          .css({
            top: top+'px',
            right: right+'px'
          })
          .on('click', function (e) {
            input.val('');
            if (callback)
              callback(input);
          })
      );
    };

    /**
     * jQuery拡張
     * static
     */
    $.extend({
      /**
       * バッチリクエスト
       */
      batchRequest: function (url, data, reload) {
        reload = reload !== false;
        $.ajax({
          url: url,
          method: 'POST',
          contentType: 'application/json',
          dataType: 'json',
          headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
          data: JSON.stringify(data)
        }).done(function (data) {
          @if (config('app.debug'))
            alert('一括処理を実行しました。ページをリロードしてください。(デバッグ時のみ)');
          @else
          if (reload)
            window.location.reload();
          @endif
//          $.notify({
//            icon: 'fa fa-success',
//            message: data.message
//          }, {
//            onShown: function () {
//              if (reload)
//                window.location.reload();
//            }
//          });
        }).fail(function () {
          $.notify({
            icon: 'fa fa-times',
            message: '一括処理は実行できませんでした'
          }, {
            type: 'danger'
          });
        });
      }
    });

    /**
     * form input clear
     */
    $('input:text[data-clearable]').each(function () {
      $(this).clearable();
    });

  });
</script>
