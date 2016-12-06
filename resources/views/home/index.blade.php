@extends('tucle::base.layout')

@include('tucle::module.datatables')

@section('content')
  <div class="container">
    <div class="page-header">
      <h1>{{ config('tucle.brand', 'TUCLE5') }}</h1>
    </div>

    <table class="table" data-provider="datatables">
      <thead>
        <tr>
          <th>
            module
          </th>
          <th data-sortable="false" data-width="1px">
          </th>
        </tr>
      </thead>
      @foreach (app('TucleIndexPresenter')->entries() as $presenter)
      <tbody>
      <tr>
        <td>
          <div class="form-control-static">
            <a href="{{ $presenter->route('index') }}">
              {{ $presenter->getPageTitle() }}
            </a>
          </div>
        </td>
        <td>
          <a href="{{ $presenter->url() }}" class="btn btn-primary" target="_blank">
            サイトを表示
          </a>
        </td>
      </tr>
      </tbody>
      @endforeach
    </table>
  </div>
@endsection
