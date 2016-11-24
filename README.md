## 使い方 <small>(How to use)</small>

### laravelプロジェクト作成

<pre>
$ composer create-project laravel/laravel example-project 5.2.*  
</pre>

### TucleCoreをインストール

<pre>
$ composer require eyewill/tucle-core:dev-master
</pre>

... and append to config/app.php

<pre>
  'providers' => [
    ...
    Eyewill\TucleCore\TucleCoreServiceProvider::class,
    ...
  ],

</pre>

### Tucle初期設定

<pre>
$ php artisan tucle:init
</pre>

インストール後一度だけ実行してください

既にhomeという名前のルートが作成されている場合は動作せず終了します

### after run in console on windows

<pre>
$ bower install
$ npm install
$ gulp
</pre>
