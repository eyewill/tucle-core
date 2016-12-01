<?php namespace Eyewill\TucleCore\Forms;

use Eyewill\TucleCore\FormSpecs\FormSpecTextarea;

/**
 * Class FormTextarea
 * @package Eyewill\TucleCore\Forms
 * @property FormSpecTextarea $spec
 */
class FormTextarea extends FormInput
{
  public function render($model = null)
  {
    $spec = $this->spec;
    $name = $spec->getName();
    $class = $spec->getClass();
    $attributes = $spec->getAttributes()->merge([
      'class' => 'form-control',
    ])->get();

    $html = '';
    $html.= '<div class="'.$class.'">';
    $html.= $this->label();
    $html.= $this->presenter->getForm()->textarea($name, null, $attributes)->toHtml();
    $html.= $this->renderHelp();
    $html.= $this->renderError();
    $html.= '</div>';

    if ($spec->getGroup())
    {
      $html = $this->grouping($html);
    }

    return $html;
  }
}
