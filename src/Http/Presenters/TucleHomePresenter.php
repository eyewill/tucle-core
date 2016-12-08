<?php namespace Eyewill\TucleCore\Http\Presenters;

use Illuminate\Support\HtmlString;

class TucleHomePresenter
{
  protected $entryTableColumns = [
    [
      'label' => '機能',
    ],
  ];

  public function tableColumns()
  {
    return $this->entryTableColumns;
  }

  public function renderEntry($column, $presenter)
  {
    $html = '';
    $html.= '<td>';
    $html.= '<a href="'.$presenter->route('index').'">';
    $html.= e($presenter->getPageTitle());
    $html.= '</td>';

    return new HtmlString($html);
  }

  protected $views = [
    'actions' => [
      'index' => 'tucle::home.partial.actions.index',
      'rows' => 'tucle::home.partial.actions.rows'
    ],
  ];

  public function view($view)
  {
    return array_get($this->views, $view);
  }

  public function showCheckbox()
  {
    return false;
  }
}
