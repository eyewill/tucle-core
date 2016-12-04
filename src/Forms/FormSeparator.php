<?php namespace Eyewill\TucleCore\Forms;

class FormSeparator extends FormInput
{
  public function render($model = null, $orw = true)
  {
    return '<hr class="form-separator">';
  }
}
