@extends('tucle::base.layout')

@include('tucle::module.datatables')

@section('content')
  <div class="container">
    <div class="page-header">
      <h1>{{ config('tucle.brand', 'TUCLE5') }}</h1>
    </div>

    @section('entries')
      @include('tucle::partial.entries', [
        'entries' => $entries,
        'presenter' => $presenter,
      ])
    @show

  </div>
@endsection
