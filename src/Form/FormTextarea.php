<?php namespace Eyewill\TucleCore\Form;

use Eyewill\TucleCore\FormSpecs\FormSpecTextarea;

class FormTextarea extends FormInput
{
  public function render()
  {
    /** @var FormSpecTextarea $spec */
    $spec = $this->spec;
    $name = $spec->getName();
    $attributes = $spec->getAttributes()->merge([
      'class' => 'form-control',
    ])->get();

    $html = $this->label();
    $html.= $this->presenter->getForm()->textarea($name, null, $attributes)->toHtml();
    $html.= $this->renderHelp();
    $html.= $this->renderError();
    if ($spec->getGroup())
    {
      $html = $this->grouping($html);
    }

    return $html;
  }
}
