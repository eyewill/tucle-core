<?php namespace Eyewill\TucleCore;

class Module
{
  protected $attributes = [];
  protected $presenter;

  public function __construct($module = [])
  {
    $this->name = array_get($module, 'name', $module);
    $allows = array_get($module, 'allows');
    if (is_array($allows))
      $allows = implode(',', $allows);
    $this->allows = $allows;
    $class = 'App\\Http\\Presenters\\'.studly_case($this->name).'Presenter';
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