### リリースノート

#### develop

- フロントエンドトップページはデフォルトで管理画面にリダイレクト

##### 変更点

- 

##### アップグレード

- 

##### 推奨するアップグレード

- routesとServiceProviderを更新 
~~~
$ php artisan tucle:init --only=routes,providers --force
~~~

#### 0.5.4

##### 変更点

- Model用トレイトとしてSortableを追加
- Model用インターフェースとしてSortableInterfaceを追加
- ModelPresenterにソート用のメソッドを追加
- indexページのactionに並び替え用リンクを追加
- CMSのルートを/adminに変更
- ServiceProviderを参照する形からコピーする形に変更(以前のServiceProviderは0.7.0で削除される予定)
- .env.localを生成するようにした
- config/tucle.phpにcopyrightとpowered_byを追加
- Bugsnagを追加
- login,logoutのurlを/admin/login,/admin/logoutに変更
- user,eventlogのURLを修正
- デフォルトのシードを追加

##### アップグレード

- ServiceProviderを更新
~~~
$ php artisan tucle:init --only=providers --force
~~~

- authモジュールを更新
~~~
$ php artisan tucle:init --only=auth --force
~~~

- Authenticateミドルウェアを更新
~~~
$ php artisan tucle:init --only=kernel --force
~~~

- レイアウトを更新
~~~
$ php artisan tucle:init --only=layout --force
~~~

##### 推奨するアップグレード

- 並び替え機能を追加したいモジュールにTucleBuilderを再実行(テーブルにorderフィールドが存在する必要あり)
~~~
$ php artisan tucle:builder module_name --force
~~~

- config/tucle.phpにcopyrightとpowered_byを追加
~~~
  'copyright' => 'Eyewill',

  'powered_by' => 'Tucle5.2',
~~~

- composerとapp.phpを更新
~~~
$ php artisan tucle:init --only=composer,config --force
~~~
- .envにBUGSNAG_API_KEYをセット
~~~
BUGSNAG_API_KEY=*****
~~~

~~~
# シードを更新
$ php artisan tucle:init --only=seeds --force
~~~

#### 0.5.0

##### 変更点

- 一覧ページのペジネーションを上部にも設置、datatablesに関するviewを更新
- tucle:initでException/Handler.phpとviews/common.blade.phpを生成
- tucle:initでHttp/Kernel.phpをコピーする形に変更
- tucle:initでRole Middlewareをコピーする形に変更
- Expire Middlewareを追加
- tucle.event_log.user_credential_keyをtucle.auth_credential_keyに変更
- Userモジュールを更新
- layoutを更新
- .btn-actionsに隙間ができる問題を修正

##### アップグレード

- datatablesに関連するviewを上書きしている場合、更新が必要
- assetsのビルド
~~~
$ npm run prod
~~~
- Http/Kernel.php、Role Middleware、Expire Middlewareを生成
~~~
$ php artisan tucle:init --only=kernel --force
~~~
- リソースを更新
~~~
> gulp
~~~

##### 推奨するアップグレード

- Exception/Handler.phpとviews/common.blade.phpを生成
~~~
$ php artisan tucle:init --only=exception --force
~~~
- layoutを更新
~~~
$ php artisan tucle:init --only=layout --force
~~~
- AuthControllerとauth viewを生成
~~~
$ php artisan tucle:init --only=auth --force
~~~
- EventLogPresenterのloginIdFilterValues()を更新
~~~
  public function loginIdFilterValues()
  {
    $name = config('tucle.auth_credential_key');
    return User::query()->pluck($name, $name);
  }
~~~
- usersのmigrationファイルを更新
~~~
- $table->string('name');
- $table->string('email')->unique();
+ $table->string('user_name');
+ $table->string('login_id')->unique();
~~~
- Userモジュールを更新
~~~
$ php artisan tucle:makeuser --force
~~~
- 言語ファイルを更新
~~~
$ php artisan tucle:init --only=lang --force
~~~
- config/tucle.phpからevent_log.user_credential_keyを削除し、auth_credential_keyを追加
~~~
   'event_log' => [
     'enabled' => true,
-    'user_credential_key' => 'email',
   ],

+  'auth_credential_key' => 'login_id',
 ];
~~~

#### 0.4.0

##### 変更点

- FakeModelGeneratorを追加
- datatables/make.blade.phpを廃止
- PresenterにrenderMakeDataTablesScriptを追加
- ModelPresenterの$tableColumnsでフィルタの設定ができるようにした(statusのフィルタはデフォルトで有効)
- EventLog更新
- cmsミドルウェアグループを追加
- config/module.phpにpresenterを指定できるようにした(presenterを指定しない場合、nameからPresenter名を推測する)
- PresenterにhasSearchBoxプロパティを追加
- UserPresenterに無効列、無効フィルタ、ロールフィルタを追加

##### アップグレード

- ダミーフォルダとダミーファイルを生成
~~~
$ php artisan tucle:init --only=asset
~~~
- make.blade.phpの設定をPresenterの$dataTablesに移動
~~~php
  protected $dataTables = [
    'options' => [
      'columnDefs' => [
        [
          'className' => 'align-middle text-center',
          'targets' => [1,2,3],
        ],
      ],
    ],
  ];
~~~

- Presenterのfilters設定からstatusの定義を削除

##### 推奨するアップグレード

- seederのfactory内の定義をFakeModelGeneratorを使う形に書き換える
~~~php
  $fakemodel = fakemodel();
  $fakemodel->setData([
    'title' => $fakemodel->faker()->sentence,
  ]);
  $fakemodel->image('main_image', 50);
  $fakemodel->publishes();
  return $fakemodel->getData();
~~~
- Presenterのfilters設定を$tableColumnsに移動
~~~php
  $tableColumns =  [
    // ...
    [
      'name' => 'top_flg',
      'label' => 'top_flg',
      'filter' => [
        'label' => 'トップフラグ',
        'type' => 'select',
        'values' => [
          '' => '未選択',
          0 => 'あり',
          1 => 'なし',
        ]
      ],
    ],
    // ...
  ],
~~~

- EventLogのViewを更新
~~~
$ php artisan tucle:init --only=eventlog --force
~~~
- Kernelを更新
~~~
$ php artisan tucle:init --only=kernel --force
~~~
- config/tucle.phpのmodulesエントリーにpresenterを追加
~~~
  'modules' => [
    [
      'name' => 'news',
      'allows' => 'manager',
      'model' => \App\News::class,
      'presenter' => \App\Http\Presenters\NewsPresenter::class,
    ],
  ],
  [
    // ...
  ],
~~~
- Userモジュールを更新
~~~
$ php artisan tucle:makeuser --force
~~~

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
