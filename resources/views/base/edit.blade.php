@extends('layout')

@include('tucle::module.ckeditor')
@include('tucle::module.fileinput')
@include('tucle::module.datetimepicker')

@section('content')
  <div class="container">

    @section('breadcrumbs')
    {{ $presenter->renderBreadCrumbs('edit') }}
    @show

    @section('page-header')
    <div class="page-header">
      <div class="row">
        <div class="col-md-6">
          <h1 class="form-title">{{ $presenter->getPageTitle() }}</h1>
        </div>
        <div class="col-md-6">
          @include($presenter->view('partial.actions.edit'))
        </div>
      </div>
    </div>
    @show


    {{ $presenter->getForm()->model($model, ['url' => $presenter->route('update', $model), 'method' => 'put', 'files' => true, 'autocomplete' => 'off']) }}
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