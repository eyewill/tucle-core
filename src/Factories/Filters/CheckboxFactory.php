<?php namespace Eyewill\TucleCore\Factories\Filters;

use Eyewill\TucleCore\Filters\FilterCheckbox;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;
use Illuminate\Support\Collection;

/**
 * Class CheckboxFactory
 * @package Eyewill\TucleCore\Factories\Forms
 *
 * valueがセットされている場合は単一のチェックボックス、
 * valuesがセットされている場合は複数のチェックボックスを生成する
 */
class CheckboxFactory extends Factory
{
  public function __construct($attributes = [], $mergeAttributes = [])
  {
    array_set($attributes, 'inline',
      array_get($attributes, 'inline', false));
    array_set($attributes, 'value',
      array_get($attributes, 'value'));
    array_set($attributes, 'attr.class', array_get($attributes, 'attr.class', ''));

    parent::__construct($attributes, $mergeAttributes);
  }

  public function make(ModelPresenter $presenter)
  {
    return app()->make(FilterCheckbox::class, [$presenter, $this]);
  }

  /**
   * @return bool
   */
  public function isMulti()
  {
    return is_null($this->getValue());
  }

  public function getValues(ModelPresenter $presenter, $model = null)
  {
    $name = $this->getName();
    $func = camel_case($name).'FilterValues';

    if (method_exists($presenter, $func))
    {
      $values = $presenter->{$func}($model);
    }
    elseif ($this->hasAttribute('values'))
    {
      $values = $this->getAttribute('values');
    }
    elseif ($this->hasAttribute('value') && $this->hasAttribute('label'))
    {
      $values = [$this->getAttribute('value') => $this->getAttribute('label')];
    }
    else
    {
      $values = [];
    }

    if ($values instanceof Collection)
    {
      $values = $values->toArray();
    }

    return $values;
  }

  public function getValue()
  {
    return array_get($this->attributes, 'value');
  }

  public function getInline()
  {
    return array_get($this->attributes, 'inline');
  }
}