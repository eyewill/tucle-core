@extends('tucle::base.layout')

@include('tucle::module.datatables')

@section('content')
  <div class="container">
    <div class="page-header">
      <h1>{{ config('tucle.brand', 'TUCLE5') }}</h1>
    </div>

    <table id="entries" class="table" data-provider="datatables">
      <thead>
        <tr>
          <th></th>
          <th>
            module
          </th>
          <th data-sortable="false" data-width="1px">
          </th>
        </tr>
      </thead>
      <tbody>
      @foreach (app('TucleIndexPresenter')->entries() as $presenter)
      <tr>
        <td></td>
        <td>
          <a href="{{ $presenter->route('index') }}">
            {{ $presenter->getPageTitle() }}
          </a>
        </td>
        <td>
          <a href="{{ $presenter->url() }}" class="btn btn-primary btn-sm" target="_blank">
            <span class="fa fa-globe fa-lg"></span>
            サイトを表示
          </a>
        </td>
      </tr>
      @endforeach
      </tbody>
    </table>
    @if (config('app.debug'))
      <a href="#" data-action-clear="#entries">クリア</a>
    @endif
  </div>
@endsection
