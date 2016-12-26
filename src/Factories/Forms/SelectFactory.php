<?php namespace Eyewill\TucleCore\Factories\Forms;

use Eyewill\TucleCore\Forms\FormSelect;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;
use Illuminate\Support\Collection;

class SelectFactory extends Factory
{
  public function __construct($attributes = [], $mergeAttributes = [])
  {
    array_set($attributes, 'empty_label',
      array_get($attributes, 'empty_label', false));
    array_set($attributes, 'values',
      array_get($attributes, 'values', []));

    parent::__construct($attributes, $mergeAttributes);
  }

  public function make(ModelPresenter $presenter)
  {
    return app()->make(FormSelect::class, [$presenter, $this]);
  }

  public function getEmptyLabel()
  {
    return array_get($this->attributes, 'empty_label');
  }

  public function setValues($presenter)
  {
  }

  public function getValues(ModelPresenter $presenter, $model = null)
  {
    $name = $this->getName();
    $func = camel_case($name).'Values';

    if (method_exists($presenter, $func))
    {
      $values = $presenter->{$func}($model);
    }
    else
    {
      $values = array_get($this->attributes, 'values');
    }

    if ($values instanceof Collection)
    {
      $values = $values->toArray();
    }

    return $values;
  }
}