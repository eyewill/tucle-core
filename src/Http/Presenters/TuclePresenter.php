<?php namespace Eyewill\TucleCore\Http\Presenters;

use Eyewill\TucleCore\Navigation;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\HtmlString;

class TuclePresenter
{
  public function title()
  {
    $title = '';

    if (!app()->environment('production'))
    {
      $title.= app()->environment().' - ';
    }

    $title.= config('tucle.brand', 'TUCLE5');

    return $title;
  }

  public function navigation()
  {
    $html = '';
    foreach (navigation()->all() as $navigation)
    {
      $html.= $this->renderMenu($navigation);
    }

    return new HtmlString($html);
  }

  protected function renderMenu($navigation)
  {
    if ($navigation->disabled())
    {
      return '';
    }

    foreach(explode(',', $navigation->names()) as $module)
    {
      $module = module($module);
      if (app(Gate::class)->denies('show-'.$module->name(), $module->model))
      {
        return '';
      }
    }

    $html = '';

    if ($navigation->hasGroup())
    {
      $html.= $this->renderGroup($navigation);
    }
    else
    {
      $url = $navigation->url();
      $label = $navigation->label();
      $html.= '<li>';
      $html.= '<a href="'.$url.'">';
      $html.= e($label);
      $html.= '</a>';
      $html.= '</li>';
    }

    return $html;
  }

  protected function renderGroup(Navigation $navigation)
  {
    $html = '';

    $html.= '<li class="dropdown">';
    $html.= '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="dropdown">';
    $html.= e($navigation->label()).' <span class="caret"></span>';
    $html.= '</a>';
    $html.= '<ul class="dropdown-menu" role="menu">';
    foreach ($navigation->group() as $entry)
    {
      $html.= $this->renderMenu($entry);
    }
    $html.= '</ul>';
    $html.= '</li>';

    return $html;
  }
}
