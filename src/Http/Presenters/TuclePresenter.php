<?php namespace Eyewill\TucleCore\Http\Presenters;

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\HtmlString;

class TuclePresenter
{
  public function navigation()
  {
    $html = '';
    $modules = config('tucle.modules', []);
    foreach ($modules as $module)
    {
      if (is_array($module))
      {
        $name = array_get($module, 'name');
        $allows = array_get($module, 'allows');
        if (is_array($allows))
          $allows = implode(',', $allows);
        if (app(Gate::class)->allows($allows))
        {
          $html.= $this->renderMenu($name);
        }
        continue;
      }

      $html.= $this->renderMenu($module);
    }

    return new HtmlString($html);
  }

  protected function renderMenu($name)
  {
    $html = '';
    $presenter = app('App\\Http\\Presenters\\'.studly_case($name).'Presenter');
    $url = $presenter->route('index');
    $label = $presenter->getPageTitle();
    $html.= '<li>';
    $html.= '<a href="'.$url.'">';
    $html.= e($label);
    $html.= '</a>';
    $html.= '</li>';

    return $html;
  }
}
