<?php namespace Eyewill\TucleCore\Forms;

use Eyewill\TucleCore\Factories\Forms\CheckboxFactory;

/**
 * Class FormCheckbox
 * @package Eyewill\TucleCore\Forms
 *
 * @property CheckboxFactory $factory
 */
class FormCheckbox extends FormInput
{
  public function label()
  {
    if (!$this->factory->isMulti())
    {
      return '';
    }

    return parent::label();
  }

  protected function renderComponent($model)
  {
    $factory = $this->factory;
    $inputName = $factory->getName();
    $values = $factory->getValues($this->presenter, $model);
    $checkedValues = $factory->getCheckedValues($this->presenter, $model);

    if ($factory->isMulti())
    {
      $inputName.= '[]';
    }

    $html = '';
    $html.= '<input type="hidden" name="'.$inputName.'" value="">';
    if ($factory->getInline())
    {
      $html.= '<div>';
      foreach ($values as $value => $label)
      {
        $checked = in_array($value, $checkedValues) ? true : null;
        $html.= '<label class="checkbox-inline">';
        $html.= $this->presenter->getForm()->checkbox($inputName, $value, $checked)->toHtml();
        $html.= e($label);
        $html.= '</label> ';
      }
      $html.= '</div>';
    }
    else
    {
      foreach ($values as $value => $label)
      {
        $checked = in_array($value, $checkedValues) ? true : null;
        $html.= '<div class="checkbox">';
        $html.= '<label>';
        $html.= $this->presenter->getForm()->checkbox($inputName, $value, $checked)->toHtml();
        $html.= e($label);
        $html.= '</label>';
        $html.= '</div>';
      }
    }

    return $html;
  }
}