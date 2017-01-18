@extends('tucle::base.layout')

@section('content')
<div class="container">

  @section('breadcrumbs')
  {{ $presenter->renderBreadCrumbs('show') }}
  @show

  @section('page-header')
  <div class="page-header">
    <div class="row">
      <div class="col-md-6">
        <h1 class="form-title">{{ $presenter->getPageTitle($model) }}</h1>
      </div>
      <div class="col-md-6">
        @include($presenter->view('partial.actions.show'))
      </div>
    </div>
  </div>
  @show

  <div class="row">
    <div class="col-md-12">

      {{ $presenter->renderDetails($model) }}

    </div>
  </div>
</div>
@endsection