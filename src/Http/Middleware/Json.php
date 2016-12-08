<?php

namespace Eyewill\TucleCore\Http\Middleware;

use Closure;

class Json
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      if (!$request->isJson())
      {
        return response()->json([
          'status' => 'error',
          'message' => 'JSON形式のリクエストである必要があります',
        ]);
      }

      return $next($request);
    }
}
