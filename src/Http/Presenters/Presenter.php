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
      'edit' => 'tucle::partial.actions.edit',
      'show' => 'tucle::partial.actions.show',
    ],
    'datatables' => [
      'init' => 'tucle::partial.datatables.init',
      'actions' => [
        'entries' => 'tucle::partial.datatables.actions.entries',
        'rows' => 'tucle::partial.datatables.actions.rows',
      ],
    ],
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

  public function hasRowActions()
  {
    return true;
  }
}