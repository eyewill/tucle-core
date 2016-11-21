<table class="table">
  @foreach ($models as $model)
    <tr>
      <td>
        <div class="form-control-static">
          <a href="{{ $model->route('index') }}">
            {{ $model->title }}
          </a>
        </div>
      </td>
      <td>
        <a href="{{ $model->site('index') }}" class="btn btn-primary" target="_blank">
          サイトを表示
        </a>
      </td>
    </tr>
  @endforeach
</table>
