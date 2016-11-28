@extends('tucle::layout')

@include('tucle::module.ckeditor')
@include('tucle::module.fileinput')

@section('content')
  <div class="container">

    {{ $presenter->renderBreadCrumbs(['label' => $model->title, 'route' => ['show', $model]], ['label' => '編集']) }}

    {{ $presenter->renderPageActions($model, ['back', 'show']) }}

    <div class="page-header">
      <h1>{{ $model->title }}</h1>
    </div>
    <div class="row">
      <div class="col-md-12">

        {{ $presenter->getForm()->model($model, ['route' => [$presenter->routeName('show'), $model], 'method' => 'put', 'files' => true]) }}
          {{ $presenter->renderForm($model) }}

          <div class="form-separator"></div>
          <button type="submit" class="btn btn-primary">更新</button>
        {{ $presenter->getForm()->close() }}

      </div>
    </div>
  </div>
@endsection