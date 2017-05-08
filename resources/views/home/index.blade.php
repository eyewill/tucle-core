@extends('layout')

@section('content')
  <div class="container">
    <h1 class="text-center">{{ $presenter->title() }}</h1>

    <hr class="form-separator">

    <div class="row">
      <div class="col-sm-10 col-sm-offset-1">
        <table class="table table-bordered table-hover">
          <tr>
            <th class="active">機能</th>
            <th class="active text-right">登録数</th>
            <th class="active text-right">最終更新日</th>
          </tr>
          @foreach ($entries as $module)
            <tr class="clickable">
              <td>
                <a href="{{ $module['url'] }}" style="display:block">
                  {{ $module['label'] }}
                </a>
              </td>
              <td class="text-right">
                <a href="{{ $module['url'] }}" style="display:block">
                  {{ $module['count'] }}
                </a>
              </td>
              <td class="text-right">
                <a href="{{ $module['url'] }}" style="display:block">
                  {{ $module['updated_at'] }}
                </a>
              </td>
            </tr>
          @endforeach
        </table>
      </div>
    </div>
  </div>
@endsection

