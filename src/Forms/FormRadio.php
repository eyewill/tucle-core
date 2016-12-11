<?php namespace Eyewill\TucleCore\Forms;

use Eyewill\TucleCore\Factories\Forms\RadioFactory;

/**
 * Class FormRadio
 * @package Eyewill\TucleCore\Forms
 *
 * @property RadioFactory $spec
 */
class FormRadio extends FormInput
{
  protected function renderComponent($model)
  {
    $spec = $this->spec;
    $name = $spec->getName();
    $html = '';
    if ($spec->getInline())
    {
      $html.= '<div>';
      foreach ($spec->getValues() as $value => $label)
      {
        $html.= '<label class="radio-inline">';
        $html.= $this->presenter->getForm()->radio($name, $value)->toHtml();
        $html.= e($label);
        $html.= '</label>';
      }
      $html.= '</div>';
    }
    else
    {
      foreach ($spec->getValues() as $value => $label)
      {
        $html.= '<div class="radio">';
        $html.= '<label>';
        $html.= $this->presenter->getForm()->radio($name, $value)->toHtml();
        $html.= e($label);
        $html.= '</label>';
        $html.= '</div>';
      }
    }

    return $html;
  }
}