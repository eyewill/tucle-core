<?php namespace Eyewill\TucleCore\Http\Presenters;

class Presenter
{
  protected $entryTableColumns = [];
  protected $views = [];
  protected $showCheckbox = false;

  public function tableColumns()
  {
    return $this->entryTableColumns;
  }

  public function view($view)
  {
    return array_get($this->views, $view);
  }

  public function showCheckbox()
  {
    return $this->showCheckbox;
  }

}