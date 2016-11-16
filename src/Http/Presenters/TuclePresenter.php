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

  public function jsFlashMessage()
  {
    $html = '';
    if (session()->has('success'))
    {
      $html = view('partial.message', [
        'type'    => 'success',
        'icon'    => 'fa fa-check',
        'message' => session()->get('success'),
      ]);
    }
    elseif (session()->has('error'))
    {
      $html = view('partial.message', [
        'type'    => 'danger',
        'icon'    => 'fa fa-times',
        'message' => session()->get('error'),
      ]);
    }
    elseif (session()->has('notice'))
    {
      $html = view('partial.message', [
        'type' => 'info',
        'icon' => 'fa fa-info',
        'message'      => session()->get('notice'),
      ]);
    }
    elseif (session()->has('warning'))
    {
      $html = view('partial.message', [
        'type' => 'warning',
        'icon' => 'fa fa-exclamation-triangle',
        'message'      => session()->get('warning'),
      ]);
    }

    return new HtmlString($html);
  }

  public function jsRestAction()
  {
    $html = view('partial.actions');
    return new HtmlString($html);
  }

}
