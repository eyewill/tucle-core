<?php namespace Eyewill\TucleCore;

class Module
{
  protected $attributes = [];
  protected $presenter;

  public function __construct($module = [])
  {
    if (is_array($module))
    {
      $this->attributes = $module;
    }
    else
    {
      $this->attributes = [
        'name' => $module,
        'allows' => null,
        'model' => null,
      ];
    }
    if (array_has($this->attributes, 'presenter'))
    {
      $class = $this->attributes['presenter'];
    }
    else
    {
      $class = 'App\\Http\\Presenters\\'.studly_case($this->name).'Presenter';
    }
    $this->presenter = app($class);
  }

  public function __set($name, $value)
  {
    $this->attributes[$name] = $value;
  }

  public function __get($name)
  {
    return $this->attributes[$name];
  }

  public function name()
  {
    return $this->name;
  }

  public function allows()
  {
    return $this->allows;
  }

  public function label()
  {
    return $this->presenter->getPageTitle();
  }
  
  public function url()
  {
    return $this->presenter->route('index');
  }
}