<?php
/**
 * @var \Eyewill\TucleCore\Http\Presenters\ModelPresenter $presenter
 */
?>
<div class="row">
  <div class="col-xs-12">
    <div class="btn-toolbar pull-right">

      <a href="{{ $presenter->route('index') }}" class="btn btn-default">
        <span class="fa fa-list"></span>
        一覧に戻る
      </a>

      <a href="{{ $presenter->route('create') }}" class="btn btn-primary">
        <span class="fa fa-file-o"></span>
        新規作成
      </a>

      <span class="btn-separator"></span>

      @if ($presenter->hasRoute('show'))
        @if ($model instanceof \Eyewill\TucleCore\Contracts\Eloquent\ExpirableInterface)
          @if ($model->published())
            <a href="{{ $presenter->route('show', $model) }}" class="btn btn-primary" target="_blank">
              <span class="fa fa-external-link"></span>
              サイト
            </a>
          @else
            @if ($presenter->hasRoute('preview'))
              <a href="{{ $presenter->route('preview', $model) }}" class="btn btn-primary" target="_blank">
                <span class="fa fa-external-link"></span>
                プレビュー
              </a>
            @endif
          @endif
        @else
          <a href="{{ $presenter->route('show', $model) }}" class="btn btn-primary" target="_blank">
            <span class="fa fa-external-link"></span>
            サイト
          </a>
        @endif
      @endif

      <button data-action="destroy" data-url="{{ $presenter->route('delete', $model) }}" class="btn btn-danger">
        <span class="fa fa-trash"></span>
        削除
      </button>

    </div>
  </div>
</div>
