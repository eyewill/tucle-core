<?php
use Eyewill\TucleCore\Contracts\ModuleManager;
use Eyewill\TucleCore\Contracts\NavigationManager;

if (!function_exists('role')) {
  function role($name)
  {
    return collect(config('auth.roles'))->first(function ($index, $value) use ($name) {
      return ($value['name'] == $name);
    });
  }
}

if (!function_exists('module')) {
  function module($name = null)
  {
    if (is_null($name))
    {
      return app()->make(ModuleManager::class);
    }

    return app()->make(ModuleManager::class)->find($name);
  }
}

if (!function_exists('navigation')) {
  function navigation($name = null)
  {
    if (is_null($name))
    {
      return app()->make(NavigationManager::class);
    }

    return app()->make(NavigationManager::class)->find($name);
  }
}
