@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default panel-login">
                    <div class="panel-heading">ログイン</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('login') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has(config('tucle.auth_credential_key')) ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">ログインID</label>

                                <div class="col-md-6">
                                    <input id="email" type="text" class="form-control" name="{{ config('tucle.auth_credential_key') }}" value="{{ old(config('tucle.auth_credential_key')) }}">

                                    @if ($errors->has(config('tucle.auth_credential_key')))
                                        <span class="help-block">
                                        <strong>{{ $errors->first(config('tucle.auth_credential_key')) }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">パスワード</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password">

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember"> Remember Me
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-btn fa-sign-in"></i> ログイン
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
