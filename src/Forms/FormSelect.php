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
    $factory = $this->factory;
    $name = $factory->getName();
    $attributes = $factory->getAttributes()->get();
    $values = $factory->getValues();
    $value = null;

    if (is_null($model))
    {
      $value = $factory->getValue();
    }

    if ($factory->getEmptyLabel())
    {
      $values = ['' => $factory->getEmptyLabel()]+$values;
    }

    return $this->presenter->getForm()->select($name, $values, $value, $attributes)->toHtml();
  }
}
