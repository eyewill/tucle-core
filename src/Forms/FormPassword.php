<?php namespace Eyewill\TucleCore\Forms;

class FormPassword extends FormInput
{
  protected function renderComponent($model)
  {
    $spec = $this->spec;
    $name = $spec->getName();
    $attributes = $spec->getAttributes()->get();
    return $this->presenter->getForm()->password($name, $attributes)->toHtml();
  }
}
