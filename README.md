## インストール <small>(Install)</small>

<pre>
# laravelプロジェクト作成
$ composer create-project laravel/laravel example-project 5.2.*  
</pre>

<pre>
# TucleCoreをインストール
$ cd example-project
$ composer require eyewill/tucle-core:dev-master
</pre>



<pre>
# app.phpに追加
$ vi config/app.php

  'providers' => [
    ...
    Eyewill\TucleCore\TucleCoreServiceProvider::class,
    ...
  ],

</pre>

## 使い方 <small>(How to use)</small>

### 初期化

<pre>
$ php artisan tucle:init
</pre>

インストール後一度だけ実行してください。

実行するとルートフォルダに.tucleというファイルが作成されます。

.tucleが作成されていて生成対象のファイルが存在する場合、そのタスクはスキップします。

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

### Presenter

<pre>
  // セレクトボックス
  public $forms = [
    ...
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
</pre>