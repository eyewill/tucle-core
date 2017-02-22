<?php namespace Eyewill\TucleCore\Http\Middleware;

use Closure;
use Gate;

class Role
{
  function handle($request, Closure $next)
  {
    // ログインセッションが切れている場合は
    // Authenticationに任せる
    if (!$request->user())
    {
      return $next($request);
    }

    foreach (config('tucle.modules', []) as $module)
    {
      if (is_array($module))
      {
        $name = $module['name'];
        $allows = $module['allows'];
        if (is_array($allows))
          $allows = implode(',', $allows);
        if ($request->is($name) || $request->is($name.'/*'))
        {
          if (Gate::denies($allows))
          {
            $url = role($request->user()->role)['default_url'];
            return redirect()->to($url)
              ->with('error', '権限が不足しています');
          }
        }
      }
    }

    return $next($request);
  }
}