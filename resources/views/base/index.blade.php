@extends('layout')

@include('tucle::module.datatables')
@include('tucle::module.datatables.controls')

@section('content')
<div class="container">

  @section('breadcrumbs')
  {{ $presenter->renderBreadCrumbs('index') }}
  @show

  @section('page-header')
  <div class="page-header">
    <div class="row">
      <div class="col-md-6">
        <h1 class="form-title">{{ $presenter->getPageTitle() }}</h1>
      </div>
      <div class="col-md-6">
        @include($presenter->view('partial.actions.index'))
      </div>
    </div>
  </div>
  @show

  @section('entries')
    <div class="table-actions" style="display:none">
      <div class="row">
        <div class="col-sm-12">
          @include($presenter->view('partial.datatables.actions.entries'))
        </div>
      </div>
    </div>
    @include('tucle::partial.entries', [
      'entries' => $entries,
      'presenter' => $presenter,
    ])
  @show

  @include($presenter->view('partial.datatables.filters'))

</div>
@endsection


