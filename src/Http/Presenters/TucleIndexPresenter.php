<?php namespace Eyewill\TucleCore\Http\Presenters;

class TucleIndexPresenter
{
  public function entries()
  {
    $modules = config('tucle.modules', []);
    $entries = [];
    foreach ($modules as $module)
    {
      $entries[] = app('App\\Http\\Presenters\\'.studly_case($module).'Presenter');
    }

    return $entries;
  }

}
