<?php namespace Eyewill\TucleCore\Http\Presenters;

use Illuminate\Support\Collection;

class Presenter
{
  protected $tableColumns = [];
  protected $views = [];
  protected $showCheckbox = false;

  protected $defaultViews = [
    'actions' => [
      'index' => 'tucle::partial.actions.index',
      'rows' => 'tucle::partial.actions.rows',
      'edit' => 'tucle::partial.actions.edit',
      'show' => 'tucle::partial.actions.show',
    ],
    'datatables' => 'tucle::partial.datatables',
  ];

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

}