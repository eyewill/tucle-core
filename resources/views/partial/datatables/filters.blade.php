@section('content')
  @parent
  <div style="display:none">
    <div class="table-filters">
      フィルター:
      @foreach($presenter->getFilters() as $spec)
        <button id="{{ $presenter->filterTriggerId($spec) }}"
                class="btn btn-sm btn-default filter-none"
                data-toggle="modal"
                data-target="#{{ $presenter->filterModalId($spec) }}"
                data-label="{{ $presenter->filterLabel($spec) }}">
          {{ $presenter->filterLabel($spec) }}
        </button>
      @endforeach
      <button id="filter_clear" class="btn btn-sm btn-warning" disabled>クリア</button>
    </div>
  </div>

  @foreach($presenter->getFilters() as $spec)
    <div class="modal fade" id="{{ $presenter->filterModalId($spec) }}" tabindex="-1">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            フィルタに追加
          </div>
          <div class="modal-body">
            {{ $presenter->renderFilter($spec) }}
          </div>
          <div class="modal-footer">
            <button class="btn btn-default" data-dismiss="modal">閉じる</button>
          </div>
        </div>
      </div>
    </div>
  @endforeach

@endsection