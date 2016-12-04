<?php namespace Eyewill\TucleCore\Forms;

use Eyewill\TucleCore\FormSpecs\FormSpecSelect;

/**
 * Class FormSelect
 * @package Eyewill\TucleCore\Forms
 */
class FormSelect extends FormInput
{
  /** @var  FormSpecSelect */
  protected $spec;

  protected function renderComponent($model)
  {
    $spec = $this->spec;
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
