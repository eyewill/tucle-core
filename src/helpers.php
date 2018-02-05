<?php
use Eyewill\TucleCore\Contracts\ModuleManager;
use Eyewill\TucleCore\Contracts\NavigationManager;
use Eyewill\TucleCore\Support\Role;

if (!function_exists('role')) {
  function role($name)
  {
    return \Eyewill\TucleCore\Support\Role::get($name);
  }
}

if (!function_exists('roles')) {
  function roles()
  {
    return Role::roles();

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
      $params = request()->all();
    }

    $params = array_except($params, ['_method', '_token', 'remember_token', 'password', 'created_at', 'updated_at']);

    if (config('tucle.event_log.enabled'))
    {
      DB::table('event_logs')->insert([
        'login_id' => $user->{config('tucle.auth_credential_key', 'email')},
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

if (!function_exists('fakemodel')) {
  function fakemodel()
  {
    return app()->make(\Eyewill\TucleCore\Database\FakeModelGenerator::class);
  }
}

if (!function_exists('visibilities')) {
  function visibilities($user)
  {
    return \Eyewill\TucleCore\Support\Role::visibilities($user);
  }
}