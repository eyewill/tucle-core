<?php namespace Eyewill\TucleCore\Http\Presenters;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

class Presenter
{
  protected $routes = [];
  protected $breadCrumbs = [];
  protected $tableColumns = [];
  protected $views = [];
  protected $viewBase = '';
  protected $showCheckbox = false;
  /** @var RouteManager */
  protected $router;
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
        'actions' => [
          'entries' => 'tucle::partial.datatables.actions.entries',
          'rows' => 'tucle::partial.datatables.actions.rows',
        ],
      ],
    ],
  ];

  public function __construct(RouteManager $router)
  {
    $router->setRoutes($this->routes);
    $this->router = $router;

    $this->breadCrumbs = array_merge([[
      'label' => config('tucle.brand', 'TUCLE5'),
      'url' => '/',
    ]], $this->breadCrumbs);
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

    throw new Exception('"'.$view.'" view file not found.');
  }

  public function showCheckbox()
  {
    return $this->showCheckbox;
  }

  public function hasRowActions()
  {
    return true;
  }

  function routeName($action = null)
  {
    return $this->router->routeName($action);
  }

  function route($route = null, $parameters = [])
  {
    return $this->router->route($route, $parameters);
  }

  public function renderBreadCrumbs($breadCrumb = null)
  {
    $breadCrumbs = $this->breadCrumbs;
    if (func_num_args() > 1)
    {
      $breadCrumbs = array_merge($breadCrumbs, func_get_args());
    }
    elseif (!is_null($breadCrumb))
    {
      $breadCrumbs[] = $breadCrumb;
    }

    $html = '';
    $html.= '<ol class="breadcrumb">';
    foreach ($breadCrumbs as $crumb)
    {
      $url = false;
      if (array_has($crumb, 'url')) $url = $crumb['url'];
      elseif (array_has($crumb, 'route')) $url = $this->route($crumb['route']);
      if ($url)
        $html.= '<li><a href="'.$url.'">'.$crumb['label'].'</a></li>';
      else
        $html.= '<li>'.$crumb['label'].'</li>';
    }
    $html.= '</ol>';

    return new HtmlString($html);
  }

  public function getRouter()
  {
    return $this->router;
  }
}