<?php namespace Eyewill\TucleCore\Form;

use Eyewill\TucleCore\FormTypes\FormTypeTextarea;

class FormTextarea extends FormInput
{
  public function render()
  {
    /** @var FormTypeTextarea $type */
    $type = $this->type;
    $name = $type->getName();
    $attributes = $type->getAttributes()->mergeAttributes([
      'class' => 'form-control',
    ]);

    $html = $this->label();
    $html.= $this->presenter->getForm()->textarea($name, null, $attributes)->toHtml();
    $html.= $this->renderHelp();
    $html.= $this->renderError();
    if ($type->getGroup())
    {
      $html = $this->grouping($html);
    }

    return $html;
  }
}
