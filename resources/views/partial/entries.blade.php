<table id="entries" class="table" data-provider="datatables">
  <thead>
  <tr>
    @if ($presenter->showCheckbox())
    <th></th>
    @endif
    @foreach ($presenter->tableColumns() as $column)
      <th>{{ $column['label'] }}</th>
    @endforeach
    <th data-orderable="false" data-searchable="false" data-class-name="text-center" data-width="1px"></th>
  </tr>
  </thead>

  <tbody>
  @foreach ($entries as $entry)
    <tr>
      @if ($presenter->showCheckbox())
      <td>{{ $presenter->checkboxId($entry) }}</td>
      @endif
      @foreach ($presenter->tableColumns() as $column)
        {{ $presenter->renderEntry($column, $entry) }}
      @endforeach
      <td>
        <div class="btn-actions">
          @include($presenter->view('actions.rows'))
        </div>
      </td>
    </tr>
  @endforeach
  </tbody>

</table>
@if (config('app.debug'))
<a href="#" data-action-clear="#entries">クリア</a>
@endif

