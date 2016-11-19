<?php namespace Eyewill\TucleCore;

use File;
use Route;

class Initialize
{
  public function generator()
  {
    yield $this->updateHttpRoutes();
  }

  public function updateHttpRoutes()
  {
    $homeRoute = Route::getRoutes()->getByName('home');
    $routesPath = app_path().'/Http/routes.php';
    if (is_null($homeRoute))
    {
      File::put($routesPath, <<<__PHP___
<?php

/**
 * Index
 * route GET /
 * name home
*/
Route::get('/', function () {
  return view('tucle::tucle.index');
})->middleware('auth')->name('home');
__PHP___
      );
      return $routesPath.' generated.';
    }
    else
    {
      return $routesPath.' already exists.';
    }
  }

}