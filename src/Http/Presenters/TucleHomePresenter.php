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

  public function viewIndexActions()
  {
    return 'tucle::home.partial.actions.index';
  }

  public function viewListActions()
  {
    return 'tucle::home.partial.actions.list';
  }
}
