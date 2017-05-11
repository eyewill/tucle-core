<?php
  /**
   * @var \Eyewill\TucleCore\Http\Presenters\ModelPresenter $presenter
   */
?>
<table id="entries" class="table" data-provider="datatables" style="display: none">
  <thead>
    {{ $presenter->renderTableColumns() }}
  </thead>

  <tbody>
  @foreach ($entries as $entry)
    <tr class="{{ $presenter->renderTableRowClass($entry) }}">
      {{ $presenter->renderTableRow($entry) }}
      @if ($presenter->hasRowActions())
      <td>
        <div class="btn-actions">
          @include($presenter->view('partial.datatables.actions.rows'))
        </div>
      </td>
      @endif
    </tr>
  @endforeach
  </tbody>

</table>

<a href="#" data-action-clear="#entries">表示をリセット</a>

