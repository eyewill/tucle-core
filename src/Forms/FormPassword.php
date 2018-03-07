<?php namespace Eyewill\TucleCore\Forms;

class FormPassword extends FormInput
{
  public function renderComponent($model = null)
  {
    $spec = $this->factory;
    $name = $spec->getName();
    $attributes = $spec->getAttributes()->get();
    $component = $this->presenter->getForm()->password($name, $attributes)->toHtml();

    return $this->addon($component);
  }
}
