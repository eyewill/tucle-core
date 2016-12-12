<div id="table-actions" class="btn-actions">
  <button class="btn btn-default" data-table-action="clearall" disabled>
    <span class="fa fa-square-o"></span>
    全クリア
  </button>
  <button class="btn btn-primary" data-table-action="put" data-url="{{ $presenter->route('batch') }}" data-attributes='{"published_at":"now","terminated_at":""}' disabled>
    <span class="fa fa-check"></span>
    公開
  </button>
  <button class="btn btn-warning" data-table-action="put" data-url="{{ $presenter->route('batch') }}" data-attributes='{"terminated_at":"-1 minute"}' disabled>
    <span class="fa fa-ban"></span>
    公開終了
  </button>
  <button class="btn btn-danger" data-table-action="delete" data-url="{{ $presenter->route('batch') }}" disabled>
    <span class="fa fa-trash-o"></span>
    削除
  </button>
</div>
