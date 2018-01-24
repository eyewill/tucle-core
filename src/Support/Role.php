<?php namespace Eyewill\TucleCore\Support;

use Illuminate\Support\Collection;

class Role
{
  protected static $roles;

  /**
   * @return Collection
   */
  public static function roles()
  {
    if (is_null(static::$roles))
    {
      static::$roles = collect(config('tucle.roles', []));
    }

    return static::$roles;
  }

  /**
   * @param $name
   * @return array
   */
  public static function get($name)
  {
    return static::roles()->first(function ($value) use ($name) {
      return ($value['name'] == $name);
    });
  }

  public static function visibilities($user)
  {
    $visibilities = array_get(static::get($user->role), 'visibilities', []);
    if (!is_array($visibilities))
    {
      $visibilities = [$visibilities];
    }

    $visibilities[] = $user->role;

    return $visibilities;
  }
}