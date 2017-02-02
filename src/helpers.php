<?php
if (!function_exists('role')) {
  function role($name)
  {
    return collect(config('auth.roles'))->first(function ($index, $value) use ($name) {
      return ($value['name'] == $name);
    });
  }
}
