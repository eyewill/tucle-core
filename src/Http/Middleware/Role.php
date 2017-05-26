<?php namespace Eyewill\TucleCore\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Http\Request;

class Role
{
  function handle(Request $request, Closure $next)
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
        $name = $path = $module['name'];

        if ($request->route()->getPrefix())
        {
          $path = $request->route()->getPrefix().'/'.$name;
        }
        if ($request->is($path) || $request->is($path.'/*'))
        {
          if (app(Gate::class)->denies('show-'.$name, $module['model']))
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