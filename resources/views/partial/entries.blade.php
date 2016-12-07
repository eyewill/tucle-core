<table id="entries" class="table" data-provider="datatables">
  <thead>
  <tr>
    <th></th>
    @foreach ($presenter->tableColumns() as $column)
      <th>{{ $column['label'] }}</th>
    @endforeach
    <th data-orderable="false" data-searchable="false" data-class-name="text-center" data-width="1px"></th>
  </tr>
  </thead>

  <tbody>
  @foreach ($entries as $entry)
    <tr>
      <td></td>
      @foreach ($presenter->tableColumns() as $column)
        {{ $presenter->renderEntry($column, $entry) }}
      @endforeach

      <td>
        <div class="btn-actions">
          @include($presenter->viewActions(), [
            'entry' => $entry,
          ])
        </div>
      </td>
    </tr>
  @endforeach
  </tbody>

</table>
@if (config('app.debug'))
<a href="#" data-action-clear="#entries">クリア</a>
@endif