<?php namespace Eyewill\TucleCore\Form;

class FormAttributes
{
  protected $attributes = [];

  /**
   * FormAttributes constructor.
   * @param array $attributes
   */
  public function __construct(array $attributes = [])
  {
    $this->attributes = $attributes;
  }

  /**
   * @param array $attributes
   */
  public function set(array $attributes)
  {
    $this->attributes = $attributes;
  }

  /**
   * @return array
   */
  public function get()
  {
    return $this->attributes;
  }

  /**
   * @param $attributes
   * @return $this
   */
  public function merge($attributes)
  {
    $this->attributes = array_merge($attributes, $this->attributes);
    return $this;
  }
}