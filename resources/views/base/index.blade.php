@extends('tucle::base.layout')

@include('tucle::module.datatables')

@section('content')
<div class="container">

  @section('breadcrumbs')
  {{ $presenter->renderBreadCrumbs(['label' => '一覧']) }}
  @show

  @section('page-header')
  <div class="page-header">
    <div class="row">
      <div class="col-md-6">
        <h1 class="form-title">{{ $presenter->getPageTitle() }}</h1>
      </div>
      <div class="col-md-6">
        @section('actions')
          @include($presenter->view('actions.index'))
        @show
      </div>
    </div>
  </div>
  @show

  @section('entries')
    <div class="table-actions" style="display:none">
      <div class="row">
        <div class="col-sm-12">
          <div id="table-actions" class="btn-actions">
            <button class="btn btn-default" data-table-action="clearall" disabled>
              <span class="fa fa-square-o"></span>
              全クリア
            </button>
            <button class="btn btn-primary" data-table-action="put" data-url="{{ $presenter->route('batch') }}" data-attributes='{"published_at":"now","terminated_at":""}' disabled>
              <span class="fa fa-check"></span>
              公開
            </button>
            <button class="btn btn-warning" data-table-action="put" data-url="{{ $presenter->route('batch') }}" data-attributes='{"terminated_at":"-1 minute"}' disabled>
              <span class="fa fa-ban"></span>
              公開終了
            </button>
            <button class="btn btn-danger" data-table-action="delete" data-url="{{ $presenter->route('batch') }}" disabled>
              <span class="fa fa-trash-o"></span>
              削除
            </button>
          </div>
        </div>
      </div>
    </div>
    @include('tucle::partial.entries', [
      'entries' => $entries,
      'presenter' => $presenter,
    ])
  @show

</div>
@endsection
