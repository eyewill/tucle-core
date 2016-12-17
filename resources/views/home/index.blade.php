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
  <script>
    $(function(){
      var factory = $.extend({}, DataTablesFactory, {
        options: {
          columnDefs: [
            { className: 'align-middle', targets: '_all' },
            { type: "html", targets: "_all" }
          ],
          select: false
        }
      });
      factory.make();
    });
  </script>
@endsection

