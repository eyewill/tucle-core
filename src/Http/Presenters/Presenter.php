<?php namespace Eyewill\TucleCore\Http\Presenters;

use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

class Presenter
{
  protected $routes = [];
  protected $breadCrumbs = [];
  protected $tableColumns = [];
  protected $views = [];
  protected $showCheckbox = false;
  /** @var RouteManager */
  protected $router;

  protected $defaultViews = [
    'actions' => [
      'index' => 'tucle::partial.actions.index',
      'edit' => 'tucle::partial.actions.edit',
      'show' => 'tucle::partial.actions.show',
    ],
    'datatables' => [
      'actions' => [
        'entries' => 'tucle::partial.datatables.actions.entries',
        'rows' => 'tucle::partial.datatables.actions.rows',
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
    if (!$this->views instanceof Collection)
    {
      $this->views = collect(array_replace_recursive($this->defaultViews, $this->views));
    }
    return array_get($this->views, $view);
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