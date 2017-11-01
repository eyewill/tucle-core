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
