<?php namespace Eyewill\TucleCore\Http\Presenters;

class RouteManager
{
  protected $routes = [];
  protected $routeParams = [];

  public function setRoutes($routes)
  {
    $this->routes = $routes;
  }

  public function setRouteParams($routeParams = [])
  {
    foreach ($routeParams as $route => $params)
    {
      if (is_array($params))
      {
        $this->routeParams[$route] = $params;
      }
      else
      {
        $this->routeParams[$route] = [$params];
      }
    }
  }

  public function routeName($action = null)
  {
    return $this->routes[$action];
  }

  public function route($route = null, $parameters = [])
  {
    if (is_array($route))
    {
      $parameters = $route;
      $route = array_shift($parameters);
    }

    if (!is_array($parameters))
    {
      $parameters = [$parameters];
    }

    if (isset($this->routeParams[$route]))
    {
      $parameters = array_merge($this->routeParams[$route], $parameters);
    }

    return route($this->routes[$route], $parameters);
  }

  public function only($only, $params)
  {
    if (!is_array($only))
    {
      $only = [$only];
    }

    if (!is_array($params))
    {
      $params = array_slice(func_get_args(), 1);
    }

    foreach ($only as $route)
    {
      $this->routeParams[$route] = $params;
    }
  }

  public function except($except, $params)
  {
    if (!is_array($except))
    {
      $except = [$except];
    }

    if (!is_array($params))
    {
      $params = array_slice(func_get_args(), 1);
    }

    $routes = array_diff($this->routes, $except);
    foreach ($routes as $route)
    {
      $this->routeParams[$route] = $params;
    }
  }

}