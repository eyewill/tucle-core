<?php namespace Eyewill\TucleCore\Factories\Forms;

use Eyewill\TucleCore\Forms\FormCheckbox;
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
    array_set($attributes, 'values',
      array_get($attributes, 'values', []));
    array_set($attributes, 'checked_values',
      array_get($attributes, 'checked_values', []));

    parent::__construct($attributes, $mergeAttributes);
  }

  public function make(ModelPresenter $presenter)
  {
    $this->setValue($presenter);
    $this->setCheckedValues($presenter);
    $this->setValues($presenter);
    return app()->make(FormCheckbox::class, [$presenter, $this]);
  }

  public function setCheckedValues($presenter)
  {
    $name = $this->getName();
    $value = null;
    $func = snake_case($name).'CheckedValues';
    if (method_exists($presenter, $func))
    {
      $value = $presenter->$func();
      if (!is_array($value))
      {
        $value = [$value];
      }
      $this->attributes['checked_values'] = $value;
    }

  }

  public function getCheckedValues()
  {
    return array_get($this->attributes, 'checked_values');
  }

  public function isMulti()
  {
    return is_null($this->getValue());
  }

  public function setValues($presenter)
  {
    if ($this->isMulti())
    {
      $func = camel_case($this->getName()).'Values';
      if (is_callable([$presenter, $func]))
      {
        $values = $presenter->{$func}();
        if ($values instanceof Collection)
        {
          $values = $values->toArray();
        }
        $this->attributes['values'] = $values;
      }
    }
    else
    {
      $this->attributes['values'] = [$this->getValue() => $this->getLabel()];
    }
  }

  public function getValues()
  {
    return array_get($this->attributes, 'values');
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