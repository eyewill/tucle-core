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
      if (is_array($module))
      {
        $name = array_get($module, 'name');
        $role = array_get($module, 'role');
        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->allows($role))
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
