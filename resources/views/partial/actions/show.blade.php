<div class="row">
  <div class="col-xs-12">
    <div class="btn-toolbar pull-right">
      <a href="{{ $presenter->route('index') }}" class="btn btn-default">
        <span class="fa fa-list"></span>
        一覧
      </a>
      @if ($model->candidates())
        <a href="{{ $presenter->route('preview', $model) }}" class="btn btn-primary" target="_blank">
          <span class="fa fa-globe"></span>
          プレビュー
        </a>
      @elseif ($model->published())
        <a href="{{ $model->url() }}" class="btn btn-primary" target="_blank">
          <span class="fa fa-globe"></span>
          サイト
        </a>
      @else
        <button class="btn btn-default" disabled>
          <span class="fa fa-ban"></span>
          公開期間は終了しました
        </button>
      @endif
      <a href="{{ $presenter->route('edit', $model) }}" class="btn btn-primary">
        <span class="fa fa-edit"></span>
        編集
      </a>
      <a href="{{ $presenter->route('create') }}" class="btn btn-primary">
        <span class="fa fa-file-o"></span>
        作成
      </a>
    </div>
  </div>
</div>
