<table class="table" data-provider="datatables">
  <thead>
  <tr>
    @foreach ($tableColumns as $column)
      <th>{{ $column['label'] }}</th>
    @endforeach
    <th></th>
  </tr>
  </thead>

  <tbody>
  @foreach ($entries as $entry)
    <tr>
      @foreach ($tableColumns as $column)
        {{ $presenter->entry($column, $entry) }}
      @endforeach

      <td>
        <a href="{{ $presenter->route('edit', [$entry]) }}" class="btn btn-primary">編集</a>
      </td>

    </tr>
  @endforeach
  </tbody>

</table>

