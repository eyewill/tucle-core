<div class="btn-actions">
  <button class="btn btn-default" data-table-action="clearall" disabled>
    <span class="fa fa-square-o"></span>
    全クリア
  </button>
  @if ($presenter->hasRoute('batch.publish'))
  <button class="btn btn-primary" data-batch-url="{{ $presenter->route('batch.publish') }}" data-batch-name="publish" disabled>
    <span class="fa fa-check"></span>
    公開
  </button>
  @endif
  @if ($presenter->hasRoute('batch.terminate'))
  <button class="btn btn-warning" data-batch-url="{{ $presenter->route('batch.terminate') }}" data-batch-name="terminate" disabled>
    <span class="fa fa-ban"></span>
    公開終了
  </button>
  @endif
  <button class="btn btn-danger" data-batch-url="{{ $presenter->route('batch.delete') }}" data-batch-name="delete" data-batch-confirm=":count件のレコードを削除します。よろしいですか？" disabled>
    <span class="fa fa-trash-o"></span>
    削除
  </button>
</div>
