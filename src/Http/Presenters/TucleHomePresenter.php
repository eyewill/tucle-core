<?php namespace Eyewill\TucleCore\Http\Presenters;

use Illuminate\Support\HtmlString;

class TucleHomePresenter extends Presenter
{
  protected $tableColumns = [
    [
      'label' => '機能',
    ],
  ];

  protected $views = [
    'actions' => [
      'index' => 'tucle::home.partial.actions.index',
    ],
    'datatables' => [
      'init' => 'tucle::home.partial.datatables.init',
      'actions' => [
        'rows' => 'tucle::home.partial.datatables.actions.rows',
      ],
    ],
  ];

  public function renderTableColumn($column, $presenter)
  {
    $html = '';
    $html.= '<td>';
    $html.= '<a href="'.$presenter->route('index').'">';
    $html.= e($presenter->getPageTitle());
    $html.= '</td>';

    return new HtmlString($html);
  }
}
