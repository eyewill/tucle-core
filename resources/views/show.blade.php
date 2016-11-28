@extends('tucle::layout')

@section('content')
<div class="container">
  {{ $presenter->renderBreadCrumbs(['label' => $model->title]) }}

  {{ $presenter->renderPageActions($model, ['back', 'edit', 'create']) }}

  <div class="page-header">
    <h1>{{ $model->title }}</h1>
  </div>

  <div class="row">
    <div class="col-md-12">

      {{ $presenter->renderDetails($model) }}

    </div>
  </div>
</div>
@endsection