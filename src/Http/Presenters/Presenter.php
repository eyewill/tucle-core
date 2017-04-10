<?php namespace Eyewill\TucleCore\Http\Presenters;

use Exception;
use Illuminate\Http\Request;

class Presenter
{
  protected $pageTitle;
  protected $routes = [];
  protected $breadCrumbs = [];
  protected $tableColumns = [];
  protected $views = [];
  protected $viewBase = '';
  protected $showCheckbox = false;
  /** @var RouteManager */
  protected $router;
  protected $request;
  protected $defaultViews = [
    'partial' => [
      'actions' => [
        'index' => 'tucle::partial.actions.index',
        'create' => 'tucle::partial.actions.create',
        'edit' => 'tucle::partial.actions.edit',
        'show' => 'tucle::partial.actions.show',
      ],
      'datatables' => [
        'make' => 'tucle::partial.datatables.make',
        'filters' => 'tucle::partial.datatables.filters',
        'actions' => [
          'entries' => 'tucle::partial.datatables.actions.entries',
          'rows' => 'tucle::partial.datatables.actions.rows',
        ],
      ],
    ],
  ];
  protected $hasRowActions = true;
  protected $url;

  public function __construct(RouteManager $router, Request $request)
  {
    $this->router = $router;
    $this->request = $request;

    $this->router->setRoutes($this->routes);
  }

  public function getPageTitle()
  {
    return $this->pageTitle;
  }

  public function tableColumns()
  {
    return $this->tableColumns;
  }

  public function view($view)
  {
    $customView = $view;
    if (!empty($this->viewBase))
    {
      $customView = $this->viewBase.$view;
    }

    if (view()->exists($customView))
    {
      return $customView;
    }

    if (array_has($this->defaultViews, $view))
    {
      return array_get($this->defaultViews, $view);
    }

    throw new Exception('"'.$customView.'" view file not found.');
  }

  public function showCheckbox()
  {
    return $this->showCheckbox;
  }

  public function hasRowActions()
  {
    return $this->hasRowActions;
  }

  function routeName($action = null)
  {
    return $this->router->routeName($action);
  }

  function route($route = null, $parameters = [])
  {
    return $this->router->route($route, $parameters);
  }

  public function hasRoute($route)
  {
    return in_array($route, array_keys($this->routes));
  }

  protected function getBreadCrumbs($name, $request)
  {
    return [];
  }

  public function renderBreadCrumbs($name)
  {
    $manager = new BreadCrumbManager();

    $breadCrumbs = array_merge(
      $this->breadCrumbs,
      $this->getBreadCrumbs($name, $this->request)
    );

    return $manager->render($breadCrumbs, $this->router);
  }

  public function getRouter()
  {
    return $this->router;
  }

  public function url()
  {
    if (is_null($this->url))
    {
      return url('/');
    }

    return $this->url;
  }
}