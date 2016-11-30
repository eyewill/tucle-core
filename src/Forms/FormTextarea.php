<?php namespace Eyewill\TucleCore\Forms;

use Eyewill\TucleCore\FormSpecs\FormSpecTextarea;

class FormTextarea extends FormInput
{
  public function render()
  {
    /** @var FormSpecTextarea $spec */
    $spec = $this->spec;
    $name = $spec->getName();
    $width = $spec->getWidth();
    $attributes = $spec->getAttributes()->merge([
      'class' => 'form-control',
    ])->get();

    $html = '';
    $html.= '<div class="'.$width.'">';
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
