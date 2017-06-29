## インストール <small>(Install)</small>

<pre>
# laravelプロジェクト作成
$ composer create-project "laravel/laravel=~5.2.0" example-project
</pre>

<pre>
# TucleCoreをインストール
$ cd example-project
$ composer require eyewill/tucle-core:dev-master
</pre>



<pre>
# app.phpに追加
$ vi config/app.php
</pre>
~~~php
  'providers' => [
    ...
    Eyewill\TucleCore\TucleCoreServiceProvider::class,
    ...
  ],
~~~

## 使い方 <small>(How to use)</small>

### 初期化

<pre>
$ php artisan tucle:init
</pre>

インストール後一度だけ実行してください。

実行するとルートフォルダに.tucleというファイルが作成されます。

.tucleが作成されていて生成対象のファイルが存在する場合、そのタスクはスキップします。

### ユーザー管理モジュールを作成

<pre>
$ php artisan tucle:makeuser --force
</pre>

ユーザー管理画面を作成します
データベースのマイグレーションが終わってから実行してください
--forceをつけない場合、User.phpは更新されません

### 再生成

<pre>
$ php artisan tucle:init --force --only=assets,packages
</pre>

強制的に上書き実行させる場合は--forceをつけてください。

--only=で任意のタスクを実行できます。

--listで実行できるタスクの一覧を表示します。

### リソースを更新 (Resources)

<pre>
# after run in console on windows
$ npm install
$ bower install
$ gulp
</pre>

必ず最初に一回実行する必要があります

### Presenter

#### 入力フォームの定義

~~~php
  public $forms = [
    ...
    // セレクトボックス
    [
      'type' => 'select',
      'name' => 'category_id',
      // 未選択時のラベルを定義(未選択状態が不要な場合はfalseをセット)
      'empty_label' => '選択してください',
    ],
    ...
  ],
  ...
  // フィールド名のキャメルケース+Valuesでセレクトボックスの値を定義できる
  public function categoryIdValues()
  {
    return ['1' => 'fuga', 2 => 'fuga'];
  }
~~~


### リリースノート

#### 0.3.0

##### 変更点

- イベントログを追加

##### アップグレード

- tucle:init --only=eventlog を実行し、migrationを行う
- イベントログを記録したいモデルにuse EventLogTrait;を追加する
- EventServiceProviderはEyewill\TucleCore\Providers\EventServiceProviderを継承するように変更する
- config/tucle.phpに以下を追加
~~~php
    'event_log' => [
      'enabled' => true,
      'user_credential_key' => 'email',
    ],
~~~
- resources/views/layout.blade.phpにイベントログへのリンクを追加
~~~php
    @if (config('tucle.event_log.enabled'))
      @can('show-eventlog', App\EventLog::class)
      <li>
        <a href="{{ url('eventlog') }}">
          <span class="fa fa-btn fa-list"></span>
          イベントログ
        </a>
      </li>
      @endcan
    @endif
~~~
- app/Http/rotes.phpを以下のように変更
~~~php
    if (Gate::allows('show-'.$module->name(), $module->model))
    {
      $updatedAt = '-';
      if (Schema::hasColumn(app($module->model)->getTable(), 'updated_at'))
      {
        $newest = app($module->model)->orderBy('updated_at', 'desc')->first();
        $updatedAt = $newest && $newest->updated_at ? $newest->updated_at->format('Y/m/d H:i') : '-';
      }
      $entries[] = [
        'label' => $module->label(),
        'url' => $module->url(),
        'count' => app($module->model)->count(),
        'updated_at' => $updatedAt,
      ];
    }
~~~
- config/tucle.phpにeventlogモジュールを追加
~~~php
'modules' => [
    // ...
    [
      'name' => 'eventlog',
      'allows' => 'manager',
      'model' => \App\EventLog::class,
    ],
],
~~~

##### 推奨するアップグレード

- AuthServiceProviderからshow-userの定義を削除し、config/tucle.phpにuserモジュールを追加する
~~~php
'modules' => [
    // ...
    [
      'name' => 'user',
      'allows' => 'manager',
      'model' => \App\User::class,
    ],
],
~~~

#### 0.2.0

##### 変更点

- tucle:base.layoutを削除し、tucle:initでコピーする形に変更

##### アップグレード

- tucle:base.layoutをインクルードしている場合はlayoutを作成し、インクルード先を変更する

#### 0.1.2

It has not been documented.
