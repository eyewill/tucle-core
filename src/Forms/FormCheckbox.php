<?php namespace Eyewill\TucleCore\Forms;

use Eyewill\TucleCore\FormSpecs\FormSpecCheckbox;

class FormCheckbox extends FormInput
{
  /** @var FormSpecCheckbox */
  protected $spec;

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
        $inputName =  $name.'[]';
        $html.= '<input type="hidden" name="'.$inputName.'" value="">';
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
        $inputName =  $name.'[]';
        $html.= '<input type="hidden" name="'.$inputName.'" value="">';
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