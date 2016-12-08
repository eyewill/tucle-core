@extends('tucle::base.layout')

@include('tucle::module.datatables')

@section('content')
  <div class="container">
    <div class="page-header">
      <h1>{{ config('tucle.brand', 'TUCLE5') }}</h1>
    </div>

    @section('entries')
      @include('tucle::partial.entries')
    @show

  </div>
@endsection


@section('script')
  @parent

  @include('tucle::partial.datatables')

@section('datatables')
  <script>
    $(function () {
      $('#entries').DataTable({
        select: false
      });
    });
  </script>
@show

@endsection
