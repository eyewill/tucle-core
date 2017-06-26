<?php
use Eyewill\TucleCore\Contracts\ModuleManager;
use Eyewill\TucleCore\Contracts\NavigationManager;

if (!function_exists('role')) {
  function role($name)
  {
    return collect(config('tucle.roles'))->first(function ($index, $value) use ($name) {
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

if (!function_exists('eventlog')) {
  function eventlog($user, $event, $description = '', $params = null)
  {
    if (is_null($params))
    {
      $params = request()->except('_method', '_token', 'password', 'created_at', 'updated_at');
    }

    if (DB::table('event_logs')->exists())
    {
      DB::table('event_logs')->insert([
        'login_id' => $user->login_id,
        'role' => $user->role,
        'event' => $event,
        'params' => json_encode($params, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        'client_ip_address' => request()->ip(),
        'description' => $description,
        'created_at' => \Carbon\Carbon::now(),
      ]);
    }
  }
}