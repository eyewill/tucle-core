<?php namespace Eyewill\TucleCore\Http\Presenters;

use Carbon\Carbon;
use Collective\Html\FormBuilder;
use Collective\Html\HtmlBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;

class ModelPresenter extends Presenter
{
  protected $form;
  protected $html;
  protected $forms = [];
  protected $showCheckbox = true;
  protected $dateFormat = [];
  protected $filters = [];

  public function __construct(RouteManager $router, Request $request, FormBuilder $form, HtmlBuilder $html)
  {
    $this->form = $form;
    $this->html = $html;

    parent::__construct($router, $request);
  }

  public function getFilters()
  {
    return $this->filters;
  }

  public function hasFilters()
  {
    return !empty($this->filters);
  }

  public function filterTriggerId($spec)
  {
    $name = array_get($spec, 'name');
    return $name.'_trigger';
  }

  public function filterModalId($spec)
  {
    $name = array_get($spec, 'name');
    return $name.'_modal';
  }

  public function filterLabel($spec)
  {
    return array_get($spec, 'label');
  }

  public function filterIndex($spec)
  {
    return array_get($spec, 'index');
  }

  public function filterMode($spec)
  {
    return array_get($spec, 'mode');
  }

  public function renderFilter($spec)
  {
    $html = '';

    $type = array_get($spec, 'type', 'select');
    $factory = app()->make('filter.'.$type, [$spec]);
    $filter = $factory->make($this);
    $html.= $filter->render();

    return new HtmlString($html);
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
      $exists = array_get($spec, 'exists', false);
      if ($exists && is_null($model))
      {
        continue;
      }
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
      $value = object_get($model, $name);

      if ($type == 'date')
      {
        $value = $this->date($model, $name);
      }

      if ($links)
      {
        $value = '<a href="'.$this->route('edit', [$model]).'">'.$value.'</a>';
      }

      $html = sprintf($template, $value);
    }

    return new HtmlString($html);
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

  public function checkboxId($model)
  {
    return $model->id;
  }
}