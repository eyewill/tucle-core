@extends('tucle::layout')

@include('tucle::module.datatables')

@section('content')
  <div class="container">
    <div class="page-header">
      <h1>{{ config('app.brand') }}</h1>
    </div>

    <table class="table" data-provider="datatables">
      <thead>
        <tr>
          <th>
            module
          </th>
          <th>
            action
          </th>
        </tr>
      </thead>
      {{ app('TucleIndexPresenter')->entries() }}
    </table>
  </div>
@endsection
