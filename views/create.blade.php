@extends('tucle::layout')

@include('tucle::module.ckeditor');

@section('content')
<div class="container">

  {{ $presenter->renderBreadCrumbs(['label' => '新規作成']) }}

  {{ $presenter->renderPageActions(null, ['back']) }}

  <div class="page-header">
    <h1>新規作成</h1>
  </div>
  <div class="row">
    <div class="col-md-12">

      <form action="{{ $presenter->route('index') }}" method="POST">
        {{ csrf_field() }}
        {{ $presenter->renderForm() }}
        <button type="submit" class="btn btn-primary">作成</button>
      </form>

    </div>
  </div>
</div>
@endsection