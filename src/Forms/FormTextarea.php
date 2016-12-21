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
  protected function renderComponent($model)
  {
    $factory = $this->factory;
    $name = $factory->getName();
    $value = null;

    $attributes = $factory->getAttributes()->get();

    if (is_null($model))
    {
      $value = $factory->getValue();
    }

    return $this->presenter->getForm()->textarea($name, $value, $attributes)->toHtml();
  }
}
