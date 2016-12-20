<div class="row">
  <div class="col-xs-12">
    <div class="btn-toolbar pull-right">
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
          <button class="btn btn-default" disabled>公開期間は終了しました</button>
        @endif
      @else
        <a href="{{ $model->url() }}" class="btn btn-primary" target="_blank">
          <span class="fa fa-external-link"></span>
          サイト
        </a>
      @endif
      <a href="{{ $presenter->route('show', $model) }}" class="btn btn-primary">
        <span class="fa fa-file-text-o"></span>
        表示
      </a>

        <span class="btn-separator"></span>

      <a href="{{ $presenter->route('create') }}" class="btn btn-primary">
        <span class="fa fa-file-o"></span>
        作成
      </a>
        <a href="{{ $presenter->route('index') }}" class="btn btn-default">
          <span class="fa fa-list"></span>
          一覧に戻る
        </a>
    </div>
  </div>
</div>
