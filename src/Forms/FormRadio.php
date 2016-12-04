<?php namespace Eyewill\TucleCore\Forms;

use Eyewill\TucleCore\FormSpecs\FormSpecRadio;

/**
 * Class FormRadio
 * @package Eyewill\TucleCore\Forms
 * @property FormSpecRadio $spec
 */
class FormRadio extends FormInput
{
  protected function renderComponent($model)
  {
    $spec = $this->spec;
    $name = $spec->getName();
    $attributes = $spec->getAttributes()->get();

    $html = '';
    $html.= '<label>&nbsp;</label>';
    $html.= '<div class="radio">';
    $html.= '<label>';
    $html.= '<input type="hidden" name="'.$name.'" value="">';
    $html.= $this->presenter->getForm()->radio($name, 1, null, $attributes)->toHtml();
    $html.= $spec->getLabel();
    $html.= '</label>';

    return $html;
  }
}