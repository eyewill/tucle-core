<?php namespace Eyewill\TucleCore\Forms;

use Eyewill\TucleCore\FormSpecs\FormSpecRadio;

/**
 * Class FormRadio
 * @package Eyewill\TucleCore\Forms
 * @property FormSpecRadio $spec
 */
class FormRadio extends FormInput
{
  public function render($model = null)
  {
    $spec = $this->spec;
    $name = $spec->getName();
    $class = $spec->getClass();
    $attributes = $spec->getAttributes()->get();

    $html = '';
    $html.= '<div class="'.$class.'">';
    $html.= '<label>&nbsp;</label>';
    $html.= '<div class="radio">';
    $html.= '<label>';
    $html.= '<input type="hidden" name="'.$name.'" value="">';
    $html.= $this->presenter->getForm()->radio($name, 1, null, $attributes)->toHtml();
    $html.= $spec->getLabel();
    $html.= '</label>';
    $html.= $this->renderHelp();
    $html.= $this->renderError();
    $html.= '</div>';
    $html.= '</div>';

    if ($spec->getGroup())
    {
      $html = $this->grouping($html);
    }

    return $html;
  }

}