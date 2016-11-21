<?php namespace Eyewill\TucleCore\Http\Presenters;

use Illuminate\Support\HtmlString;

class TuclePresenter
{
  public function navigation()
  {
    $html = '';
    $modules = config('tucle.modules', []);
    foreach ($modules as $module)
    {
      $presenter = app('App\\Http\\Presenters\\'.studly_case($module).'Presenter');
      $url = $presenter->route('index');
      $label = $presenter->getPageTitle();
      $html.= '<li>';
      $html.= '<a href="'.$url.'">';
      $html.= e($label);
      $html.= '</a>';
      $html.= '</li>';
    }

    return new HtmlString($html);
  }
}
