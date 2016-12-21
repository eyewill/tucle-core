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
    $spec = $this->factory;
    $name = $spec->getName();
    $attributes = $spec->getAttributes()->get();
    return $this->presenter->getForm()->textarea($name, null, $attributes)->toHtml();
  }
}
