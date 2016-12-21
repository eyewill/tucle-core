<?php namespace Eyewill\TucleCore\Forms;

use Eyewill\TucleCore\Factories\Forms\SeparatorFactory;

/**
 * Class FormSeparator
 * @package Eyewill\TucleCore\Forms
 * 
 * @property SeparatorFactory $factory
 */
class FormSeparator extends FormInput
{
  public function render($model = null, $orw = true)
  {
    return '<hr class="form-separator">';
  }
}
