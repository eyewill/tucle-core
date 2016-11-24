<?php
namespace Eyewill\TucleCore\Forms;


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