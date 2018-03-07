<?php namespace Eyewill\TucleCore\Filters;

use Eyewill\TucleCore\Factories\Filters\RadioFactory;

/**
 * Class FormRadio
 * @package Eyewill\TucleCore\Forms
 *
 * @property RadioFactory $factory
 */
class FilterRadio extends FilterInput
{
  public function renderComponent($model = null)
  {
    $factory = $this->factory;
    $name = $factory->getName();
    $values = $factory->getValues($this->presenter, $model);

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
        $html.= '<label class="radio-inline">';
        $html.= $this->presenter->getForm()->radio($name, $value, null, $attr)->toHtml();
        $html.= e($label);
        $html.= '</label>';
      }
      $html.= '</div>';
    }
    else
    {
      foreach ($values as $value => $label)
      {
        $html.= '<div class="radio">';
        $html.= '<label>';
        $html.= $this->presenter->getForm()->radio($name, $value, null, $attr)->toHtml();
        $html.= e($label);
        $html.= '</label>';
        $html.= '</div>';
      }
    }

    return $html;
  }
}