<?php namespace Eyewill\TucleCore\Forms;

use Eyewill\TucleCore\Factories\Forms\PriceFactory;

/**
 * Class FormPrice
 * @package Eyewill\TucleCore\Forms
 *
 * @property PriceFactory $factory
 */
class FormPrice extends FormInput
{
  protected function renderComponent($model)
  {
    $factory = $this->factory;
    $name = $factory->getName();
    $value = $factory->getDefaultValue($this->presenter, $model);
    $attributes = $factory->getAttributes()->get();

    $html = '';
    $html.= '<div class="input-group">';
    $html.= $this->presenter->getForm()->text($name, $value, $attributes)->toHtml();
    $html.= '<span class="input-group-addon">円</span>';
    $html.= '</div>';

    return $html;
  }
}
