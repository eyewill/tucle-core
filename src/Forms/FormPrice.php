<?php namespace Eyewill\TucleCore\Forms;

use Eyewill\TucleCore\Factories\Forms\PriceFactory;

/**
 * Class FormPrice
 * @package Eyewill\TucleCore\Forms
 *
 * @property PriceFactory $spec
 */
class FormPrice extends FormInput
{
  protected function renderComponent($model)
  {
    $spec = $this->spec;
    $name = $spec->getName();
    $attributes = $spec->getAttributes()->get();

    $html = '';
    $html.= '<div class="input-group">';
    $html.= $this->presenter->getForm()->text($name, null, $attributes)->toHtml();
    $html.= '<span class="input-group-addon">円</span>';
    $html.= '</div>';

    return $html;
  }
}
