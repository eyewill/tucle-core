<?php namespace Eyewill\TucleCore\Http\Presenters;

use Carbon\Carbon;
use Collective\Html\FormBuilder;
use Collective\Html\HtmlBuilder;
use DB;
use Eyewill\TucleCore\Contracts\Eloquent\ExpirableInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;

class ModelPresenter extends Presenter
{
  protected $form;
  protected $html;
  protected $forms = [];
  protected $dateFormat = [];
  protected $filters = null;
  protected $queries = [];
  protected $limit = 100;
  protected $defaultSortColumn = 'id';
  protected $defaultSortOrder = 'desc';
  protected $searchColumns = [];
  protected $hasCheckbox;

  public function __construct(RouteManager $router, Request $request, FormBuilder $form, HtmlBuilder $html)
  {
    $this->form = $form;
    $this->html = $html;

    parent::__construct($router, $request);
  }

  /**
   * 検索対象カラム
   */
  protected function searchColumns($builder)
  {
    $columns = [];
    $values = collect($this->tableColumns())
      ->filter(function ($value) {
        return array_has($value, 'name') && (!array_has($value, 'searchable') || array_get($value, 'searchable', true));
      });

    foreach ($values as $value)
    {
      $columns[] = [
        'name' => $builder->getModel()->getTable().'.'.array_get($value, 'name', ''),
        'type' => 'text',
      ];
    }

    foreach ($this->searchColumns as $value)
    {
      $columns[] = [
        'name' => array_get($value, 'column', $builder->getModel()->getTable().'.'.$value['name']),
        'type' => array_get($value, 'type', 'text'),
      ];
    }

    return $columns;
  }

  public function getEntriesBuilder($model)
  {
    if ($model instanceof Builder || $model instanceof Relation)
    {
      $builder = $model;
    }
    else
    {
      $builder = app()->make($model)->query();
    }
    if (request()->has('s'))
    {
      $builder->where(function($query) use ($builder) {
        foreach($this->searchColumns($builder) as $column)
        {
          $name = $column['name'];
          $type = $column['type'];
          if ($type == 'date')
          {
            $query->orWhere(DB::raw("DATE_FORMAT($name, '%Y/%m/%d')"), 'like', request('s').'%');
          }
          else
          {
            $query->orWhere($name, 'like', '%'.request('s').'%');
          }
        }
      });
    }

    return $builder;
  }

  public function getEntries($model)
  {
    if ($model instanceof Builder || $model instanceof Relation)
    {
      $builder = $model;
    }
    else
    {
      $builder = $this->getEntriesBuilder($model);
    }
    if (request()->get('take') != 'all')
    {
      $builder->skip(request()->get('take'));
      $builder->limit($this->limit);
    }
    $sortColumns = $this->defaultSortColumn;
    if (!is_array($sortColumns))
    {
      $sortColumns = [$sortColumns];
    }
    $sortOrders = $this->defaultSortOrder;
    if (!is_array($sortOrders))
    {
      $sortOrders = [$sortOrders];
    }
    foreach ($sortColumns as $i => $column)
    {
      $order = isset($sortOrders[$i]) ? $sortOrders[$i] : 'asc';
      $builder->orderBy($column, $order);
    }

    return $builder->get();
  }

  /**
   * 全件数取得
   * group byが含まれるSQLでも正しく動作するように、サブクエリとしてカウントする
   * @param $model
   * @return int
   */
  public function getTotal($model)
  {
    $builder = $this->getEntriesBuilder($model);
    if ($builder instanceof \Eloquent)
    {
      $query = $builder->getBaseQuery();
    }
    else
    {
      $query = $builder->getQuery();
    }

    return DB::table(DB::raw("({$builder->toSql()}) as sub"))
      ->mergeBindings($query)
      ->count();
  }

  public function getLimit()
  {
    return $this->limit;
  }

  public function renderTakeSelector($total)
  {
    $values = [];

    if (is_null($total))
    {
      $values['0'] = '1件目から'.$this->getLimit().'件目まで';
    }
    else
    {
      if ($total > $this->getLimit())
      {
        foreach (range(0, $total-1, $this->getLimit()) as $index)
        {
          if ($total < $index+$this->getLimit())
          {
            $values[$index] = ($index+1).'件目から'.$total.'件目まで';
          }
          else
          {
            $values[$index] = ($index+1).'件目から'.($index+$this->getLimit()).'件目まで';
          }
        }
      }
    }

    if (is_null($total))
    {
      $values['all'] = '全件';
    }
    else
    {
      $values['all'] = '全件('.$total.'件)';
    }

    return $this->getForm()->select('take', $values, request()->get('take'), [
      'class' => 'form-control input-sm',
      'style' => 'width: auto',
    ]);

  }

  public function renderLengthSelector($total)
  {
    $values = [
      '' => $this->getLimit().'件',
    ];

    if (is_null($total))
    {
      $values['all'] = '全レコード';
    }
    else
    {
      $values['all'] = '全レコード('.$total.'件)';
    }

    return $this->getForm()->select('take', $values, request()->get('take'), [
      'class' => 'form-control input-sm',
      'style' => 'width: auto',
    ]);
  }

  public function getFilters()
  {
    if (is_null($this->filters))
    {
      $filters = [];
      foreach ($this->tableColumns() as $index => $tableColumn)
      {
        $type = array_get($tableColumn, 'type');
        $name = array_get($tableColumn, 'name');
        $label = array_get($tableColumn, 'label');
        $filter = array_get($tableColumn, 'filter', []);
        if ($type == 'status')
        {
          $filter = array_merge([
            'name' => 'status',
            'label' => '公開状態',
            'type' => 'checkbox',
            'index' => $index,
          ], $filter);
        }
        elseif (!array_key_exists('filter', $tableColumn))
        {
          continue;
        }

        $filter = array_merge([
          'index' => $index,
          'name'  => $name,
          'label' => $label,
        ], $filter);

        $filters[] = $filter;
      }

      $this->filters = $filters;
    }

    return $this->filters;
  }

  public function hasFilters()
  {
    return !empty($this->getFilters());
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

      if ($type == 'status')
      {
        return $this->renderStatus($model);
      }

      if ($type == 'checkbox')
      {
        $value = object_get($model, 'id');
        $html = sprintf('<td>%s</td>', $value);
        return new HtmlString($html);
      }

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

  public function renderTableRowClass($model)
  {
    if ($model instanceof ExpirableInterface && !$model->published())
    {
      return 'mute';
    }

    return '';
  }

  public function renderStatus($model)
  {
    if ($model instanceof ExpirableInterface)
    {
      if ($model->published())
      {
        $label = [
          'label' => '公開中',
          'class' => 'label-primary',
          'value' => 2,
        ];
      }
      elseif ($model->candidates())
      {
        $label = [
          'label' => '公開前',
          'class' => 'label-warning',
          'value' => 1,
        ];
      }
      else
      {
        $label = [
          'label' => '公開終了',
          'class' => 'label-default',
          'value' => 3,
        ];
      }
      $html = '';
      $html.= sprintf('<td data-search="%d"><span class="label %s">%s</span></td>',$label['value'],  $label['class'], $label['label']);
      return $html;
    }

    return ' ';
  }

  public function statusFilterValues()
  {
    return [
      1 => '公開前',
      2 => '公開中',
      3 => '公開終了',
    ];
  }

  public function renderTableRow($model)
  {
    $html = '';
    foreach ($this->tableColumns() as $column)
    {
      $html.= $this->renderTableColumn($column, $model);
    }

    return new HtmlString($html);
  }

  public function setParentRouteModel($model)
  {
    $this->getRouter()->only([
      'delete_file',
    ], $model);
  }

  public function hasCheckbox()
  {
    if (is_null($this->hasCheckbox))
    {
      $columns = $this->tableColumns();
      $value = array_first($columns, function($index, $value) {
        return array_get($value, 'type') == 'checkbox';
      });
      $this->hasCheckbox = !is_null($value);
    }

    return $this->hasCheckbox;
  }
}
