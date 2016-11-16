@extends('tucle::layout')

@include('tucle::module.datatables')

@section('content')
<div class="container">

  {{ $presenter->renderBreadCrumbs(['label' => '一覧']) }}

  {{ $presenter->renderPageActions(null, ['create']) }}

  <div class="page-header">
    <h1>{{ $presenter->getPageTitle() }}</h1>
  </div>

  <div class="form-separator"></div>


  {{ $presenter->entries($entries) }}

</div>
@endsection
