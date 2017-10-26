<?php namespace Eyewill\TucleCore\Http\Presenters;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;

class Presenter
{
  protected $pageTitle;
  protected $routes = [];
  protected $breadCrumbs = [];
  protected $tableColumns = [];
  protected $views = [];
  protected $viewBase = '';
  /** @var RouteManager */
  protected $router;
  protected $request;
  protected $defaultViews = [
    'partial' => [
      'actions' => [
        'index' => 'tucle::partial.actions.index',
        'create' => 'tucle::partial.actions.create',
        'edit' => 'tucle::partial.actions.edit',
      ],
      'datatables' => [
        'filters' => 'tucle::partial.datatables.filters',
        'actions' => [
          'entries' => 'tucle::partial.datatables.actions.entries',
          'rows' => 'tucle::partial.datatables.actions.rows',
        ],
      ],
    ],
  ];
  protected $hasRowActions = true;
  protected $hasSearchBox = true;
  protected $dataTables = [];

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

  public function hasRowActions()
  {
    return $this->hasRowActions;
  }

  public function hasSearchBox()
  {
    return $this->hasSearchBox;
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


  public function renderTableColumns()
  {
    $html = '';
    $html.= '<tr class="entries_columns">';
    foreach ($this->tableColumns() as $column)
    {
      $type = array_get($column, 'type', 'text');
      $value = array_get($column, 'label', '');
      $html.= sprintf('<th data-type="%s">%s</th>', $type, $value);
    }
    if ($this->hasRowActions())
    {
      $html.= '<th data-orderable="false" data-searchable="false" data-width="1px"></th>';
    }
    $html.= '</tr>';

    return new HtmlString($html);
  }


  public function renderMakeDataTablesScript()
  {
    $options = json_encode(array_get($this->dataTables, 'options', []));
    $script =<<<__SCRIPT__
<script>
$(function(){
  var factory = $.extend({}, DataTablesFactory);
  var options = $options;
  $.each(options, function (i, val) {
    if (i == 'columnDefs') {
      val = val.concat(factory.options.columnDefs);
      factory.options.columnDefs = val;      
    } else {
      factory.options[i] = val;
    }
  });
  factory.make();
});
</script>
__SCRIPT__;

    return new HtmlString($script);
  }
}