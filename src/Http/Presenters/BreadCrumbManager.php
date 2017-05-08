<?php namespace Eyewill\TucleCore\Http\Presenters;

use Illuminate\Support\HtmlString;

class BreadCrumbManager
{
  protected $prepends;

  public function __construct()
  {
    $this->setPrepends([
      [
        'label' => config('tucle.brand', 'TUCLE5'),
        'url' => config('app.url'),
      ],
    ]);
  }

  public function setPrepends($prepends)
  {
    $this->prepends = $prepends;
  }

  public function getPrepends()
  {
    return $this->prepends;
  }

  public function render($breadCrumbs, RouteManager $router)
  {
    $breadCrumbs = array_merge($this->prepends, $breadCrumbs);

    $html = '';
    $html.= '<ol class="breadcrumb">';
    foreach ($breadCrumbs as $crumb)
    {
      $url = false;
      if (array_has($crumb, 'url')) $url = $crumb['url'];
      elseif (array_has($crumb, 'route')) $url = $router->route($crumb['route']);
      if ($url)
        $html.= '<li><a href="'.$url.'">'.$crumb['label'].'</a></li>';
      else
        $html.= '<li>'.$crumb['label'].'</li>';
    }
    $html.= '</ol>';

    return new HtmlString($html);
  }
}