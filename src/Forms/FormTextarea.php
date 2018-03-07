<?php namespace Eyewill\TucleCore\Forms;

use Eyewill\TucleCore\Factories\Forms\TextareaFactory;

/**
 * Class FormTextarea
 * @package Eyewill\TucleCore\Forms
 *
 * @property TextareaFactory $factory
 */
class FormTextarea extends FormInput
{
  public function renderComponent($model = null)
  {
    $factory = $this->factory;
    $name = $factory->getName();
    $value = $factory->getDefaultValue($this->presenter, $model);
    $attributes = $factory->getAttributes()->get();

    return $this->presenter->getForm()->textarea($name, $value, $attributes)->toHtml();
  }
}
