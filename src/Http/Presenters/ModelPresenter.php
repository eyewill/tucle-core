<?php namespace Eyewill\TucleCore\Http\Presenters;

use Carbon\Carbon;
use Codesleeve\Stapler\Attachment;
use Collective\Html\FormBuilder;
use Collective\Html\HtmlBuilder;
use Illuminate\Support\HtmlString;

class ModelPresenter extends Presenter
{
  protected $pageTitle;
  protected $form;
  protected $html;
  protected $forms = [];
  protected $showColumns = [];
  protected $routes = [];
  protected $breadCrumbs = [];
  protected $showCheckbox = true;
  protected $dateFormat = [];
  protected $routeParams = [];

  public function __construct(FormBuilder $form, HtmlBuilder $html)
  {
    $this->form = $form;
    $this->html = $html;
    $this->breadCrumbs = array_merge([[
      'label' => config('tucle.brand', 'TUCLE5'),
      'url' => '/',
    ]], $this->breadCrumbs);
  }

  public function date($model, $name)
  {
    $value = $model->$name;
    if (is_null($value))
    {
      return '';
    }

    $format = array_get($this->dateFormat, $name, 'Y/m/d H:i');

    return Carbon::parse($value)->format($format);
  }

  public function getForm()
  {
    return $this->form;
  }

  public function getHtml()
  {
    return $this->html;
  }

  public function setPageTitle($pageTitle)
  {
    $this->pageTitle = $pageTitle;
  }

  public function getPageTitle($model = null)
  {
    if (!is_null($model))
    {
      return new HtmlString($model->title ? e($model->title) : '<span class="text-muted">(未定義)</span>');
    }
    return $this->pageTitle;
  }

  public function renderForm($model = null, $position = 'main')
  {
    $html = '';

    foreach ($this->forms as $spec)
    {
      $type = array_get($spec, 'type', 'text');
      $factory = app()->make('form.'.$type, [$spec]);
      if ($factory->isPosition($position))
      {
        $form = $factory->make($this);
        $html.= $form->render($model);
      }
    }

    return new HtmlString($html);
  }

  public function renderTableColumn($column, $model)
  {
    $name  = array_get($column, 'name');
    $template = array_get($column, 'template', '<td>%s</td>');

    $method = camel_case($name).'TableColumn';
    if (method_exists($this, $method))
    {
      $html = $this->$method($model, $template);
    }
    else
    {
      $type = array_get($column, 'type');
      $links = array_get($column, 'links', false);
      $value = $model->{$name};

      if ($type == 'date')
      {
        $value = $this->date($model, $name);
      }

      if ($links)
      {
        $value = '<a href="'.$this->route('show', [$model]).'">'.$value.'</a>';
      }

      $html = sprintf($template, $value);
    }

    return new HtmlString($html);
  }

  function routeName($action = null)
  {
    return $this->routes[$action];
  }

  function route($action = null, $parameters = [])
  {
    if (is_array($action))
    {
      $parameters = $action;
      $action = array_shift($parameters);
    }

    if (!is_array($parameters))
    {
      $parameters = [$parameters];
    }

    $parameters = array_merge($this->routeParams, $parameters);

    return route($this->routes[$action], $parameters);
  }

  public function renderValues($attributes = [])
  {
    $html = '';
    foreach ($attributes as $attribute)
    {
      $type = array_get($attribute, 'type', 'text');

      $html.= $this->renderValue($type, $attribute);
    }

    return $html;
  }


  public function renderValue($type, $attribute = [], $group = true)
  {
    $attributes = array_merge($this->defaults[$type] + $this->default, $attribute);

    $this->elementRenderer->setAttributes($attributes);

    $html = '';
    if ($group)
    {
      $html.= $this->elementRenderer->group();
    }

    $html.= $this->elementRenderer->label();

    $html.= $this->elementRenderer->value();

    if ($group)
    {
      $html.= $this->elementRenderer->groupClose();
    }

    return $html;
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

  public function renderDetails($model)
  {
    $html = '';
    $html.= '<div class="dl-horizontal">';
    foreach ($this->showColumns as $column)
    {
      $html.= '<dt>';
      $html.= array_get($column, 'label');
      $html.= '</dt>';
      $html.= '<dd>';
      $value = $model->{array_get($column, 'name')};
      if ($value instanceof Attachment)
      {
        $html.= $value->originalFilename();
      }
      else
      {
        $html.= $value;
      }
      $html.= '</dd>';
    }
    $html.= '</div>';
    return new HtmlString($html);
  }

  public function renderPageActions($model, $actions = [])
  {
    $html = '';
    $html.= '<div class="row">';
    $html.= '<div class="col-xs-12">';
    $html.= '<div class="btn-toolbar pull-right">';
    foreach ($actions as $action)
    {
      $method = 'render'.studly_case($action).'PageAction';
      if (method_exists($this, $method))
      {
        $html.= $this->{$method}($model);
      }
    }
    $html.= '</div>';
    $html.= '</div>';
    $html.= '</div>';

    return new HtmlString($html);
  }

  public function renderBackPageAction($model)
  {
    $url = $this->route('index');
    return new HtmlString('<a href="'.$url.'" class="btn btn-default">一覧に戻る</a>');
  }

  public function renderCreatePageAction($model)
  {
    $url = $this->route('create');
    return new HtmlString('<a href="'.$url.'" class="btn btn-primary">作成</a>');
  }

  public function renderEditPageAction($model)
  {
    $url = $this->route('edit', $model);
    return new HtmlString('<a href="'.$url.'" class="btn btn-primary">編集</a>');
  }

  public function renderShowPageAction($model)
  {
    $url = $this->route('show', $model);
    return new HtmlString('<a href="'.$url.'" class="btn btn-primary">表示</a>');
  }

  public function getAttributeNames()
  {
    $attributeNames = [];
    foreach ($this->forms as $spec)
    {
      $type = array_get($spec, 'type', 'text');
      $factory = app()->make('form.'.$type, [$spec]);
      $attributeNames += $factory->getAttributeNames();
    }

    return $attributeNames;
  }

  public function url()
  {
    return '#';
  }

  public function checkboxId($model)
  {
    return $model->id;
  }

  public function setRouteParams($params)
  {
    if (is_array($params))
    {
      $this->routeParams = $params;
    }
    else
    {
      $this->routeParams = func_get_args();
    }
  }
}