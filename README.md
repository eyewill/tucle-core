### This package for laravel 5.3, [for 5.2 is here](https://github.com/eyewill/tucle-core/tree/0.5.x).

## インストール 

#### for Vagrant

~~~ruby
# プロジェクト用に空のフォルダを作成し、Vagurantfileの共有フォルダに追加
config.vm.synced_folder "host/path/to/example-project", "guest/path/to/example-project"
~~~

~~~
# vagrantを再起動し、共有フォルダに移動
> vagrant reload
> vagrant ssh
$ cd guest/path/to/example-project
~~~

##### ウェブサーバーとデータベースを作成

#### recommend

<pre>
# install prestissimo
$ composer global require hirak/prestissimo
</pre>

#### 共通

<pre>
# laravelプロジェクト作成
$ composer create-project "laravel/laravel=~5.3.0" .
</pre>

<pre>
# TucleCoreをインストール
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

## 使い方

### 初期化

<pre>
$ php artisan tucle:init
</pre>

インストール後一度だけ実行してください。

実行するとルートフォルダに.tucleというファイルが作成されます。

.tucleが作成されていて生成対象のファイルが存在する場合、そのタスクはスキップします。

~~~bash
# .envを更新
vi .env
~~~

~~~bash
# マイグレーション
$ php artisan migrate
~~~

### ユーザー管理モジュールを作成

<pre>
$ php artisan tucle:makeuser --force
</pre>

ユーザー管理画面を作成します
データベースのマイグレーションが終わってから実行してください
--forceをつけない場合、User.phpは更新されません

### デフォルトの管理ユーザーを作成

~~~bash
$ php artisan db:seed
~~~

### 再生成

<pre>
$ php artisan tucle:init --force --only=assets,packages
</pre>

強制的に上書き実行させる場合は--forceをつけてください。

--only=で任意のタスクを実行できます。

--listで実行できるタスクの一覧を表示します。

### リソースを更新 (Resources)

~~~
> yarn
> yarn global add bower
> bower install
> yarn run prod
# TucleCoreをpackagesフォルダから読み込む場合は
> yarn run prod-dev
~~~

必ず最初に一回実行する必要があります

### migrate buildのconfigを出力

~~~bash
> php artisan vendor:publish --provider="Primalbase\Migrate\MigrateServiceProvider"
> vi config/migrate-build.php
> php artisan migrate:build table_name
~~~

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
