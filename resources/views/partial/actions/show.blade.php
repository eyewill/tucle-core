<div class="row">
  <div class="col-xs-12">
    <div class="btn-toolbar pull-right">
      <a href="{{ $presenter->route('index') }}" class="btn btn-default">一覧に戻る</a>
      @if ($model->candidates())
        <a href="{{ $presenter->route('preview', $model) }}" class="btn btn-primary" target="_blank">プレビュー</a>
      @elseif ($model->published())
        <a href="{{ $model->url() }}" class="btn btn-primary" target="_blank">サイト</a>
      @else
        <a href="#" class="btn btn-default" disabled>公開期間は終了しました</a>
      @endif
      <a href="{{ $presenter->route('edit', $model) }}" class="btn btn-primary">編集</a>
      <a href="{{ $presenter->route('create') }}" class="btn btn-primary">作成</a>
    </div>
  </div>
</div>
