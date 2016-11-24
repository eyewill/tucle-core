<?php namespace Eyewill\TucleCore\FormSpecs;

class FormSpecSelect extends FormSpec
{
  public function __construct($spec = [])
  {
    $attributes = [
      'empty_label' => array_get($spec, 'empty_label', false),
      'values' => array_get($spec, 'values', []),
    ];

    parent::__construct($spec, $attributes);
  }

  public function getEmptyLabel()
  {
    return array_get($this->attributes, 'empty_label');
  }

  public function getValues()
  {
    return array_get($this->attributes, 'values');
  }
}