@extends('tucle::base.layout')

@section('content')
<div class="container">
  {{ $presenter->renderBreadCrumbs(['label' => $presenter->getPageTitle($model)]) }}

  @section('page-header')
  <div class="page-header">
    <div class="row">
      <div class="col-sm-8">
        <h1 class="form-title">{{ $presenter->getPageTitle($model) }}</h1>
      </div>
      <div class="col-sm-4">
        {{ $presenter->renderPageActions($model, ['back', 'edit', 'create']) }}
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