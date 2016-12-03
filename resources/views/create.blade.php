@extends('tucle::layout')

@include('tucle::module.ckeditor')
@include('tucle::module.fileinput')

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
    <div class="col-sm-8">

        {{ $presenter->renderForm() }}
        <button type="submit" class="btn btn-primary">作成</button>
    </div>
    <div class="col-sm-4">
      {{ $presenter->renderForm(null, 'sub') }}
      <button type="submit" class="btn btn-primary">作成</button>
    </div>
  </div>
  {{ $presenter->getForm()->close() }}
</div>
@endsection