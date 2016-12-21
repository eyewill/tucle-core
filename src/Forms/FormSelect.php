<?php namespace Eyewill\TucleCore\Forms;

use Eyewill\TucleCore\Factories\Forms\SelectFactory;

/**
 * Class FormSelect
 * @package Eyewill\TucleCore\Forms
 * 
 * @property SelectFactory $factory
 */
class FormSelect extends FormInput
{
  protected function renderComponent($model)
  {
    $spec = $this->factory;
    $name = $spec->getName();
    $attributes = $spec->getAttributes()->get();
    $values = $spec->getValues();
    if ($spec->getEmptyLabel())
    {
      $values = ['' => $spec->getEmptyLabel()]+$values;
    }
    return $this->presenter->getForm()->select($name, $values, null, $attributes)->toHtml();
  }
}
