<div class="row">
  <div class="col-xs-12">
    <div class="btn-toolbar pull-right">
      @if ($presenter->hasRoute('front.index'))
      <a href="{{ $presenter->route('front.index') }}" class="btn btn-primary" target="_blank">
        <span class="fa fa-external-link"></span>
        サイト
      </a>
      @endif
      @if ($presenter->hasRoute('create'))
      <a href="{{ $presenter->route('create') }}" class="btn btn-primary">
        <span class="fa fa-file-o"></span>
        作成
      </a>
      @endif
    </div>
  </div>
</div>
