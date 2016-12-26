<?php namespace Eyewill\TucleCore\Forms;

class FormStatic extends FormInput
{
  public function renderComponent($model)
  {
    $name = $this->factory->getName();
    $value = $this->factory->getDefaultValue($this->presenter, $model);

    $html = '';
    $html.= '<div class="form-control-static">';
    $html.= $this->presenter->getForm()->getValueAttribute($name, $value);
    $html.= '</div>';

    return $html;
  }

}
