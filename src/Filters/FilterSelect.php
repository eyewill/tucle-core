<?php namespace Eyewill\TucleCore\Filters;

use Eyewill\TucleCore\Factories\Filters\SelectFactory;

/**
 * Class FormSelect
 * @package Eyewill\TucleCore\Forms
 * 
 * @property SelectFactory $factory
 */
class FilterSelect extends FilterInput
{
  public function renderComponent($model = null)
  {
    $factory = $this->factory;
    $name = $factory->getName();
    $values = $factory->getValues($this->presenter, $model);

    if ($factory->getEmptyLabel())
    {
      $values = ['' => $factory->getEmptyLabel()]+$values;
    }

    $attr = array_get($factory->getAttributes(), 'attr');
    $attr = array_merge([
      'data-filter' => $this->presenter->filterIndex($factory->getAttributes()),
      'data-trigger' => '#'.$this->presenter->filterTriggerId($factory->getAttributes()),
      'data-mode' => $this->presenter->filterMode($factory->getAttributes()),
    ], $attr);
    $component = $this->presenter->getForm()->select($name, $values, null, $attr)->toHtml();

    return $this->addon($component);
  }
}
