<?php namespace Eyewill\TucleCore\Forms;

class FormPassword extends FormInput
{
  protected function renderComponent($model)
  {
    $spec = $this->factory;
    $name = $spec->getName();
    $attributes = $spec->getAttributes()->get();
    $component = $this->presenter->getForm()->password($name, $attributes)->toHtml();

    return $this->addon($component);
  }
}
