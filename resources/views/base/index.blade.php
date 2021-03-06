@extends('layout')

@include('tucle::module.datatables')
@include('tucle::module.datatables.controls')
@if ($presenter->hasSearchBox())
@include('tucle::module.datatables.search')
@endif
@if ($presenter->hasFilters())
@include($presenter->view('partial.datatables.filters'))
@endif

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
    <div style="display: none">
      <div class="table-actions">
        <div class="row">
          <div class="col-sm-12">
            @if ($presenter->hasCheckbox())
              @include($presenter->view('partial.datatables.actions.entries'))
            @endif
          </div>
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


