<?php
namespace Eyewill\TucleCore\Form;


class FormSpecAttributes
{
  protected $attributes = [];

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