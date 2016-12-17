@extends('tucle::base.layout')

@include('tucle::module.datatables')

@section('content')
<div class="container">

  @section('breadcrumbs')
  {{ $presenter->renderBreadCrumbs(['label' => '一覧']) }}
  @show

  @section('page-header')
  <div class="page-header">
    <div class="row">
      <div class="col-md-6">
        <h1 class="form-title">{{ $presenter->getPageTitle() }}</h1>
      </div>
      <div class="col-md-6">
        @include($presenter->view('actions.index'))
      </div>
    </div>
  </div>
  @show

  @section('entries')
    <div class="table-actions" style="display:none">
      <div class="row">
        <div class="col-sm-12">
          @include($presenter->view('datatables.actions.entries'))
        </div>
      </div>
    </div>
    @include('tucle::partial.entries', [
      'entries' => $entries,
      'presenter' => $presenter,
    ])
  @show

</div>
@endsection

@section('script')
  @parent
  <script>
    $(function(){
      DataTablesFactory.make();
    });
  </script>
@endsection

