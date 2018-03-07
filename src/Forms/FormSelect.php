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
  public function renderComponent($model = null)
  {
    $factory = $this->factory;
    $name = $factory->getName();
    $value = $factory->getDefaultValue($this->presenter, $model);
    $attributes = $factory->getAttributes()->get();
    $values = $factory->getValues($this->presenter, $model);

    if ($factory->getEmptyLabel())
    {
      $values = ['' => $factory->getEmptyLabel()]+$values;
    }

    $component = $this->presenter->getForm()->select($name, $values, $value, $attributes)->toHtml();

    return $this->addon($component);
  }
}
