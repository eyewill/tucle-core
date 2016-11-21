<?php namespace Eyewill\TucleCore\Http\Presenters;

use Illuminate\Support\HtmlString;

class TucleIndexPresenter
{
  public function entries()
  {
    $modules = config('tucle.modules', []);
    $html = '';
    foreach ($modules as $module)
    {
      $html.= view()->make('tucle::tucle.partial.detail', [
        'presenter' => app('App\\Http\\Presenters\\'.studly_case($module).'Presenter'),
      ])->render();
    }

    return new HtmlString($html);
  }

}
