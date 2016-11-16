<?php namespace Eyewill\TucleCore\FormTypes;

class FormTypeGroup extends FormType
{
  public function __construct($spec = [])
  {
    $attributes = [
      'forms' => array_get($spec, 'forms', []),
    ];

    parent::__construct($spec, $attributes);
  }

  /**
   * @return array
   */
  public function getForms()
  {
    return array_get($this->attributes, 'forms');
  }
}