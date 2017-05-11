
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

/**
 */
class UserPresenter extends ModelPresenter {

  /**
   */
  public $forms = [
    [
      'type' => 'group',
      'forms' => [
        [
          'type' => 'text',
          'name' => 'name',
          'label' => 'ユーザー名',
          'attr' => [
            'placeholder' => '',
          ],
          'class' => 'col-sm-6',
        ],
        [
          'type' => 'select',
          'name' => 'role',
          'label' => 'ロール',
          'empty_label' => '選択してください',
          'class' => 'col-sm-6',
        ],
      ],
    ],
    [
      'type' => 'group',
      'forms' => [
        [
          'type' => 'text',
          'name' => 'email',
          'label' => 'ログインID',
          'attr' => [
            'placeholder' => '',
          ],
          'class' => 'col-sm-6',
        ],
        [
          'type' => 'password',
          'name' => 'password',
          'label' => 'パスワード',
          'class' => 'col-sm-6',
        ],
      ],
    ],
    [
      'type' => 'checkbox',
      'name' => 'disabled',
      'label' => '無効',
      'value' => 1,
      'position' => 'sub',
    ],
  ];

  /**
   */
  public $routes = [
    'index' => 'user.index',
    'create' => 'user.create',
    'store' => 'user.store',
    'edit' => 'user.edit',
    'update' => 'user.update',
    'show' => 'user.edit',
    'preview' => 'user.preview',
    'delete' => 'user.delete',
    'delete_file' => 'user.delete_file',
    'batch.delete' => 'user.batch.delete',
  ];

  /**
   */
  public $tableColumns = [
    [
      'type' => 'checkbox',
    ],
    [
      'name' => 'name',
      'label' => 'ユーザー名',
      'links' => true,
    ],
    [
      'name' => 'email',
      'label' => 'ログインID',
    ],
    [
      'name' => 'role',
      'label' => 'ロール',
    ],
  ];

  /**
   */
  protected $breadCrumbs = [
    [
      'label' => 'ユーザー管理',
      'route' => 'index',
    ],
  ];

  /**
   */
  protected $filters = [];

  /**
   */
  protected $pageTitle = 'ユーザー管理';

  protected $hasRowActions = false;

  /**
   */
  protected $viewBase = 'user.';

  /**
   * @param $name
   * @param $request
   * @return array
   */
  protected function getBreadCrumbs($name, $request) {
    $model = $request->route('user');

    $breadCrumbs = [
      'index' => [
        [
          'label' => '一覧',
        ],
      ],
      'create' => [
        [
          'label' => '新規作成',
        ],
      ],
      'edit' => [
        [
          'label' => $this->getPageTitle($model),
        ],
      ],
    ];

    return array_get($breadCrumbs, $name, []);
  }

  public function roleValues()
  {
    return array_pluck(config('tucle.roles', []), 'label', 'name');
  }

  public function roleTableColumn($model, $format)
  {
    $values = $this->roleValues();
    $label = array_get($values, $model->role, 'label');

    return sprintf($format, $label);
  }

}