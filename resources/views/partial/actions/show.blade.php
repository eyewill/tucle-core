<div class="row">
  <div class="col-xs-12">
    <div class="btn-toolbar pull-right">

      <a href="{{ $presenter->route('index') }}" class="btn btn-default">
        <span class="fa fa-list"></span>
        一覧に戻る
      </a>

      <a href="{{ $presenter->route('create') }}" class="btn btn-primary">
        <span class="fa fa-file-o"></span>
        作成
      </a>

      <span class="btn-separator"></span>

      @if ($model instanceof \Eyewill\TucleCore\Contracts\Eloquent\ExpirableInterface)

        @if ($model->candidates())
          <a href="{{ $presenter->route('preview', $model) }}" class="btn btn-primary" target="_blank">
            <span class="fa fa-external-link"></span>
            プレビュー
          </a>
        @elseif ($model->published())
          <a href="{{ $model->url() }}" class="btn btn-primary" target="_blank">
            <span class="fa fa-external-link"></span>
            サイト
          </a>
        @else
          <button class="btn btn-default" disabled>
            <span class="fa fa-ban"></span>
            公開期間は終了しました
          </button>
        @endif
      @else
        <a href="{{ $model->url() }}" class="btn btn-primary" target="_blank">
          <span class="fa fa-external-link"></span>
          サイト
        </a>
      @endif
      <a href="{{ $presenter->route('edit', $model) }}" class="btn btn-primary">
        <span class="fa fa-edit"></span>
        編集
      </a>

      <button data-action="destroy" data-url="{{ $presenter->route('delete', $model) }}" class="btn btn-danger">
        <span class="fa fa-trash"></span>
        削除
      </button>

    </div>
  </div>
</div>
