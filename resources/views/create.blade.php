@extends('tucle::layout')

@include('tucle::module.ckeditor')
@include('tucle::module.fileinput')

@section('content')
<div class="container">

  {{ $presenter->renderBreadCrumbs(['label' => '新規作成']) }}

  {{ $presenter->renderPageActions(null, ['back']) }}

  <div class="page-header">
    <h1>新規作成</h1>
  </div>
  <div class="row">
    <div class="col-md-12">

      {{ $presenter->getForm()->open(['url' => $presenter->route('index'), 'method' => 'post', 'files' => true]) }}
        {{ $presenter->renderForm() }}
        <button type="submit" class="btn btn-primary">作成</button>
      {{ $presenter->getForm()->close() }}

    </div>
  </div>
</div>
@endsection