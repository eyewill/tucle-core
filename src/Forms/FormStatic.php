<?php namespace Eyewill\TucleCore\Forms;

class FormStatic extends FormInput
{
  public function renderComponent($model)
  {
    $name = $this->spec->getName();

    $html = '';
    $html.= '<div class="form-control-static">';
    $html.= $this->presenter->getForm()->getValueAttribute($name);
    $html.= '</div>';

    return $html;
  }

}
