<table id="entries" class="table" data-provider="datatables">
  <thead>
  <tr>
    @foreach ($tableColumns as $column)
      <th>{{ $column['label'] }}</th>
    @endforeach
    <th data-orderable="false" data-searchable="false" data-class-name="text-center" data-width="1px"></th>
  </tr>
  </thead>

  <tbody>
  @foreach ($entries as $entry)
    <tr>
      @foreach ($tableColumns as $column)
        {{ $presenter->entry($column, $entry) }}
      @endforeach

      <td>
        <div class="btn-actions">
          <a href="{{ $entry->url() }}" class="btn btn-primary" target="_blank" title="ウェブサイトを表示">
            <i class="fa fa-globe"></i>
            <span class="hidden-xs">ウェブサイトを表示</span>
          </a>
          <a href="{{ $presenter->route('edit', [$entry]) }}" class="btn btn-primary">
            <i class="fa fa-edit"></i>
            <span class="hidden-xs">編集</span>
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