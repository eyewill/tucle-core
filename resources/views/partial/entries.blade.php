<table id="entries" class="table" data-provider="datatables" style="display: none" data-show-checkbox="{{ $presenter->showCheckbox() }}" data-show-status="{{ $presenter->showStatus() }}">
  <thead>
  <tr>
    @if ($presenter->showCheckbox())
      <th></th>
    @endif
    @if ($presenter->showStatus())
      <th></th>
    @endif
    @foreach ($presenter->tableColumns() as $column)
      <th>{{ $column['label'] }}</th>
    @endforeach
    @if ($presenter->hasRowActions())
    <th data-orderable="false" data-searchable="false" data-width="1px"></th>
    @endif
  </tr>
  </thead>

  <tbody>
  @foreach ($entries as $entry)
    <tr class="{{ $presenter->renderTableRowClass($entry) }}">
      @if ($presenter->showCheckbox())
      <td>{{ $presenter->checkboxId($entry) }}</td>
      @endif
      @if ($presenter->showStatus())
        <td>
          {{ $presenter->renderStatus($entry) }}
        </td>
      @endif
      @foreach ($presenter->tableColumns() as $column)
        {{ $presenter->renderTableColumn($column, $entry) }}
      @endforeach
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

