<?php namespace Eyewill\TucleCore\Http\Presenters;

use Illuminate\Support\HtmlString;

class TuclePresenter
{
  public function navigation()
  {
    $items = [
//      [
//        'url'   => '/',
//        'label' => 'Home',
//      ],
    ];

    $modules = config('module.modules', []);
    foreach ($modules as $module)
    {
      $presenter = app('App\\Http\\Presenters\\'.studly_case($module).'Presenter');
      $items[] = [
        'url' => $presenter->route('index'),
        'label' => $presenter->getPageTitle(),
      ];
    }

    $html = view()->renderEach('partial.navigation', $items, 'item');
    return new HtmlString($html);
  }
}
