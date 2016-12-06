@extends('tucle::base.layout')

@include('tucle::module.datatables')

@section('content')
<div class="container">

  {{ $presenter->renderBreadCrumbs(['label' => '一覧']) }}

  @section('page-header')
  <div class="page-header">
    <div class="row">
      <div class="col-sm-8">
        <h1 class="form-title">{{ $presenter->getPageTitle() }}</h1>
      </div>
      <div class="col-sm-4">
        {{ $presenter->renderPageActions(null, ['create']) }}
      </div>
    </div>
  </div>
  @show

  {{ $presenter->entries($entries) }}

</div>
@endsection
