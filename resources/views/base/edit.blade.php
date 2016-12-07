@extends('tucle::base.layout')

@include('tucle::module.ckeditor')
@include('tucle::module.fileinput')
@include('tucle::module.datetimepicker')

@section('content')
  <div class="container">

    {{ $presenter->renderBreadCrumbs(['label' => $presenter->getPageTitle($model), 'route' => ['update', $model]], ['label' => '編集']) }}

    @section('page-header')
    <div class="page-header">
      <div class="row">
        <div class="col-sm-8">
          <h1 class="form-title">{{ $presenter->getPageTitle($model) }}</h1>
        </div>
        <div class="col-sm-4">
          @section('actions')
            @include($presenter->viewEditActions())
          @show
        </div>
      </div>
    </div>
    @show


    {{ $presenter->getForm()->model($model, ['route' => [$presenter->routeName('update'), $model], 'method' => 'put', 'files' => true]) }}
    <div class="row">
      <div class="col-sm-9">

        {{ $presenter->renderForm($model) }}

        <hr class="form-separator">

        <button type="submit" class="hidden-xs btn btn-primary btn-block btn-lg">更新</button>
      </div>
      <div class="col-sm-3">

        {{ $presenter->renderForm($model, 'sub') }}

        <hr class="form-separator">

        <button type="submit" class="btn btn-primary btn-block btn-lg">更新</button>
      </div>
    </div>
    {{ $presenter->getForm()->close() }}
  </div>
@endsection