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
  public function renderComponent($model = null)
  {
    $factory = $this->factory;
    $name = $factory->getName();
    $values = $factory->getValues($this->presenter, $model);
    $checkedValues = $factory->getCheckedValues($this->presenter, $model);

    $html = '';
    if ($factory->getInline())
    {
      $html.= '<div>';
      foreach ($values as $value => $label)
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
      foreach ($values as $value => $label)
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