@extends('tucle::layout')

@include('tucle::module.ckeditor')
@include('tucle::module.fileinput')

@section('content')
  <div class="container">

    {{ $presenter->renderBreadCrumbs(['label' => $presenter->getPageTitle($model), 'route' => ['show', $model]], ['label' => '編集']) }}

    @section('page-header')
    <div class="page-header">
      <div class="row">
        <div class="col-sm-8">
          <h1 class="form-title">{{ $presenter->getPageTitle($model) }}</h1>
        </div>
        <div class="col-sm-4">
          {{ $presenter->renderPageActions($model, ['back', 'show', 'create']) }}
        </div>
      </div>
    </div>
    @show


    {{ $presenter->getForm()->model($model, ['route' => [$presenter->routeName('show'), $model], 'method' => 'put', 'files' => true]) }}
    <div class="row">
      <div class="col-sm-8">

        {{ $presenter->renderForm() }}
        <button type="submit" class="btn btn-primary">更新</button>
      </div>
      <div class="col-sm-4">
        {{ $presenter->renderForm(null, 'sub') }}
        <button type="submit" class="btn btn-primary">更新</button>
      </div>
    </div>
    {{ $presenter->getForm()->close() }}
  </div>
@endsection