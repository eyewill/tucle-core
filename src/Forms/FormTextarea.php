<?php namespace Eyewill\TucleCore\Forms;

use Eyewill\TucleCore\FormSpecs\FormSpecTextarea;

/**
 * Class FormTextarea
 * @package Eyewill\TucleCore\Forms
 * @property FormSpecTextarea $spec
 */
class FormTextarea extends FormInput
{
  protected function renderComponent($model)
  {
    $spec = $this->spec;
    $name = $spec->getName();
    $attributes = $spec->getAttributes()->get();
    return $this->presenter->getForm()->textarea($name, null, $attributes)->toHtml();
  }
}
