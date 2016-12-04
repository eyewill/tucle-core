<table id="entries" class="table" data-provider="datatables">
  <thead>
  <tr>
    @foreach ($tableColumns as $column)
      <th>{{ $column['label'] }}</th>
    @endforeach
    <th data-orderable="false" data-searchable="false" data-class-name="text-center hidden-xs" data-width="240px"></th>
      <th data-orderable="false" data-searchable="false" data-class-name="text-center visible-xs"></th>
  </tr>
  </thead>

  <tbody>
  @foreach ($entries as $entry)
    <tr>
      @foreach ($tableColumns as $column)
        {{ $presenter->entry($column, $entry) }}
      @endforeach

      <td class="hidden-xs">
        <div class="btn-group">
          <a href="{{ $entry->url() }}" class="btn btn-primary">ウェブサイトを表示</a>
          <a href="{{ $presenter->route('edit', [$entry]) }}" class="btn btn-primary">編集</a>
        </div>
      </td>
      <td class="visible-xs">
        <div class="btn-group">
          <a href="{{ $entry->url() }}" class="btn btn-primary" title="ウェブサイトを表示">
            <i class="fa fa-globe"></i>
          </a>
          <a href="{{ $presenter->route('edit', [$entry]) }}" class="btn btn-primary" title="編集">
            <i class="fa fa-edit"></i>
          </a>
        </div>
      </td>
    </tr>
  @endforeach
  </tbody>

</table>
@if (config('app.debug'))
<a href="#" data-action-clear="#entries">クリア</a>
@endif