<?php namespace Eyewill\TucleCore\Filters;

use Eyewill\TucleCore\Factories\Filters\CheckboxFactory;

/**
 * Class FormCheckbox
 * @package Eyewill\TucleCore\Forms
 *
 * @property CheckboxFactory $factory
 */
class FilterCheckbox extends FilterInput
{
  public function label()
  {
    if (!$this->factory->isMulti())
    {
      return '';
    }

    return parent::label();
  }

  public function renderComponent($model = null)
  {
    $factory = $this->factory;
    $inputName = $factory->getName();
    $values = $factory->getValues($this->presenter, $model);

    if ($factory->isMulti())
    {
      $inputName.= '[]';
    }

    $attr = array_get($factory->getAttributes(), 'attr');
    $attr = array_merge([
      'data-filter' => $this->presenter->filterIndex($factory->getAttributes()),
      'data-trigger' => '#'.$this->presenter->filterTriggerId($factory->getAttributes()),
      'data-mode' => $this->presenter->filterMode($factory->getAttributes()),
    ], $attr);
    $html = '';
    if ($factory->getInline())
    {
      $html.= '<div>';
      foreach ($values as $value => $label)
      {
        $html.= '<label class="checkbox-inline">';
        $html.= $this->presenter->getForm()->checkbox($inputName, $value, null, $attr)->toHtml();
        $html.= e($label);
        $html.= '</label> ';
      }
      $html.= '</div>';
    }
    else
    {
      foreach ($values as $value => $label)
      {
        $html.= '<div class="checkbox">';
        $html.= '<label>';
        $html.= $this->presenter->getForm()->checkbox($inputName, $value, null, $attr)->toHtml();
        $html.= e($label);
        $html.= '</label>';
        $html.= '</div>';
      }
    }

    return $html;
  }
}