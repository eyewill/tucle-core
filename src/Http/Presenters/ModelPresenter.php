<?php namespace Eyewill\TucleCore\Http\Presenters;

use Codesleeve\Stapler\Attachment;
use Collective\Html\FormBuilder;
use Collective\Html\HtmlBuilder;
use Eyewill\TucleCore\Factories\FormSpecFactory;
use Illuminate\Support\HtmlString;

class ModelPresenter
{
  protected $pageTitle;
  protected $form;
  protected $html;
  protected $forms = [];
  protected $entryTableColumns = [];
  protected $showColumns = [];
  protected $views = [];
  protected $routes = [];
  protected $breadCrumbs = [];

  public function __construct(FormBuilder $form, HtmlBuilder $html)
  {
    $this->form = $form;
    $this->html = $html;
    $this->breadCrumbs = array_merge([[
      'label' => config('tucle.brand', 'TUCLE5'),
      'url' => '/',
    ]], $this->breadCrumbs);
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
      $formSpec = FormSpecFactory::make($spec);
      if ($formSpec->isPosition($position))
      {
        $form = $formSpec->makeForm($this);
        $html.= $form->render($model);
      }
    }

    return new HtmlString($html);
  }

  public function tableColumns()
  {
    return $this->entryTableColumns;
  }

  public function renderEntry($column, $entry)
  {
    $name  = array_get($column, 'name');
    $links = array_get($column, 'links', false);

    $html = '';
    $html.= '<td>';
    if ($links)
    {
      $html.= '<a href="'.$this->route('show', [$entry]).'">'.e($entry->{$name}).'</a>';
    }
    else
    {
      $html.= e($entry->{$name});
    }
    $html.= '</td>';

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
      $formSpec = FormSpecFactory::make($spec);
      $attributeNames += $formSpec->getAttributeNames();
    }

    return $attributeNames;
  }

  public function url()
  {
    return '#';
  }

  public function viewIndexActions()
  {
    return 'tucle::partial.actions.index';
  }

  public function viewListActions()
  {
    return 'tucle::partial.actions.list';
  }

  public function viewEditActions()
  {
    return 'tucle::partial.actions.edit';
  }

  public function viewShowActions()
  {
    return 'tucle::partial.actions.show';
  }
}