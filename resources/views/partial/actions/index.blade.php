<div class="row">
  <div class="col-xs-12">
    <div class="btn-toolbar pull-right">
      @if ($presenter->hasRoute('show'))
      <a href="{{ $presenter->url() }}" class="btn btn-primary" target="_blank">
        <span class="fa fa-external-link"></span>
        サイト
      </a>
      @endif
      <a href="{{ $presenter->route('create') }}" class="btn btn-primary">
        <span class="fa fa-file-o"></span>
        作成
      </a>
    </div>
  </div>
</div>
