@section('content')
  @parent

  <div class="table-search" style="display:none; text-align: right">
    <form action="{{ $presenter->route('index') }}">
      <label style="font-weight: normal">
        検索: <input type="search" name="s" class="form-control input-sm" value="{{ request('s') }}">
      </label>
      <button type="submit" class="btn btn-primary btn-sm">検索</button>
      <a href="{{ $presenter->route('index') }}" class="btn btn-default btn-sm">クリア</a>
    </form>
  </div>

@endsection