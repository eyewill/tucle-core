<?php namespace Eyewill\TucleCore\Form;

class FormAttributes
{
  protected $attributes = [];

  public function __construct($attributes = [])
  {
    $this->attributes = $attributes;
  }

  public function setAttributes($attributes)
  {
    $this->attributes = $attributes;
  }

  public function getAttributes()
  {
    return $this->attributes;
  }

  public function mergeAttributes($attributes)
  {
    return $this->attributes = array_merge($attributes, $this->attributes);
  }
}