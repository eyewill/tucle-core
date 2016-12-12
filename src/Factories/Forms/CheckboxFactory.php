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

    parent::__construct($attributes, $mergeAttributes);
  }

  public function make(ModelPresenter $presenter)
  {
    if ($this->isMulti())
    {
      $this->setValues($presenter);
    }
    else
    {
      $this->attributes['values'] = [$this->getValue() => $this->getLabel()];
    }
    return app()->make(FormCheckbox::class, [$presenter, $this]);
  }

  public function isMulti()
  {
    return is_null($this->getValue());
  }

  public function setValues($presenter)
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