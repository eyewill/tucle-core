<?php namespace Eyewill\TucleCore\Forms;

use Eyewill\TucleCore\Factories\Forms\CheckboxFactory;

/**
 * Class FormCheckbox
 * @package Eyewill\TucleCore\Forms
 *
 * @property CheckboxFactory $spec
 */
class FormCheckbox extends FormInput
{
  protected function renderComponent($model)
  {
    $spec = $this->spec;
    $name = $spec->getName();
    $inputName =  $name.'[]';
    $html = '';
    $html.= '<input type="hidden" name="'.$inputName.'" value="">';
    if ($spec->getInline())
    {
      $html.= '<div>';
      foreach ($spec->getValues() as $value => $label)
      {
        $html.= '<label class="checkbox-inline">';
        $html.= $this->presenter->getForm()->checkbox($inputName, $value)->toHtml();
        $html.= e($label);
        $html.= '</label> ';
      }
      $html.= '</div>';
    }
    else
    {
      foreach ($spec->getValues() as $value => $label)
      {
        $html.= '<div class="checkbox">';
        $html.= '<label>';
        $html.= $this->presenter->getForm()->checkbox($inputName, 1)->toHtml();
        $html.= e($label);
        $html.= '</label>';
        $html.= '</div>';
      }
    }

    return $html;
  }
}