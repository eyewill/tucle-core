<?php namespace Eyewill\TucleCore\Forms;

use Eyewill\TucleCore\Factories\Forms\RadioFactory;

/**
 * Class FormRadio
 * @package Eyewill\TucleCore\Forms
 *
 * @property RadioFactory $factory
 */
class FormRadio extends FormInput
{
  protected function renderComponent($model)
  {
    $factory = $this->factory;
    $name = $factory->getName();
    $checkedValues = [];

    if (is_null($model))
    {
      $checkedValues = $factory->getCheckedValues();
    }

    $html = '';
    if ($factory->getInline())
    {
      $html.= '<div>';
      foreach ($factory->getValues() as $value => $label)
      {
        $checked = in_array($value, $checkedValues) ? true : null;
        $html.= '<label class="radio-inline">';
        $html.= $this->presenter->getForm()->radio($name, $value, $checked)->toHtml();
        $html.= e($label);
        $html.= '</label>';
      }
      $html.= '</div>';
    }
    else
    {
      foreach ($factory->getValues() as $value => $label)
      {
        $checked = in_array($value, $checkedValues) ? true : null;
        $html.= '<div class="radio">';
        $html.= '<label>';
        $html.= $this->presenter->getForm()->radio($name, $value, $checked)->toHtml();
        $html.= e($label);
        $html.= '</label>';
        $html.= '</div>';
      }
    }

    return $html;
  }
}