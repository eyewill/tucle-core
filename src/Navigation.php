<?php namespace Eyewill\TucleCore;

class Navigation
{
  protected $attributes = [];

  public function __construct($options = [])
  {
    $this->name = array_get($options, 'name', $options);
    $allows = array_get($options, 'allows');
    if (is_array($allows))
      $allows = implode(',', $allows);
    $this->allows = $allows;
    $group = array_get($options, 'group');
    if (!is_null($group))
    {
      foreach ($group as $i => $entry)
        $group[$i] = new Navigation($entry);
    }
    $this->group = $group;
    $this->label = array_get($options, 'label');
    $this->disabled = array_get($options, 'disabled', false);
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

  public function names()
  {
    if ($this->hasGroup())
    {
      $names = [];
      foreach ($this->group as $entry)
      {
        $names[] = module()->find($entry->name)->name();
      }
      return implode(',', array_unique($names));
    }

    return $this->name;
  }

  public function allows()
  {
    if ($this->hasGroup())
    {
      $allows = [];
      foreach ($this->group as $entry)
      {
        $allows[] = module()->find($entry->name)->allows();
      }
      return implode(',', array_unique($allows));
    }

    return module()->find($this->name)->allows();
  }

  public function group()
  {
    return $this->group;
  }

  public function disabled()
  {
    return $this->disabled;
  }

  public function hasGroup()
  {
    return !empty($this->group());
  }

  public function label()
  {
    if ($this->hasGroup() || $this->label)
    {
      return $this->label;
    }

    return module()->find($this->name)->label();
  }

  public function url()
  {
    return module()->find($this->name)->url();
  }
}