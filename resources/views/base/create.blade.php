@extends('tucle::base.layout')

@include('tucle::module.ckeditor')
@include('tucle::module.fileinput')
@include('tucle::module.datetimepicker')

@section('content')
<div class="container">

  {{ $presenter->renderBreadCrumbs(['label' => '新規作成']) }}

  @section('page-header')
  <div class="row page-header">
    <div class="col-sm-8">
      <h1 class="form-title">新規作成</h1>
    </div>
    <div class="col-sm-4">
      {{ $presenter->renderPageActions(null, ['back']) }}
    </div>
  </div>
  @show

  {{ $presenter->getForm()->open(['route' => $presenter->routeName('index'), 'method' => 'post', 'files' => true]) }}
  <div class="row">
    <div class="col-sm-9">

      {{ $presenter->renderForm() }}

      <hr class="form-separator">

      <button type="submit" class="hidden-xs btn btn-primary btn-block btn-lg">作成</button>
    </div>
    <div class="col-sm-3">

      {{ $presenter->renderForm(null, 'sub') }}

      <hr class="form-separator">

      <button type="submit" class="btn btn-primary btn-block btn-lg">作成</button>

    </div>
  </div>
  {{ $presenter->getForm()->close() }}
</div>
@endsection