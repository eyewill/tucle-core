<?php namespace Eyewill\TucleCore\FormSpecs;

use Eyewill\TucleCore\Forms\FormCheckbox;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;
use Illuminate\Support\Collection;

class FormSpecCheckbox extends FormSpec
{
  public function __construct($attributes = [], $mergeAttributes = [])
  {
    array_set($attributes, 'inline',
      array_get($attributes, 'inline', false));
    array_set($attributes, 'values',
      array_get($attributes, 'values', []));

    parent::__construct($attributes, $mergeAttributes);
  }

  public function makeForm(ModelPresenter $presenter)
  {
    $this->setValues($presenter);
    return app()->make(FormCheckbox::class, [$presenter, $this]);
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

  public function getInline()
  {
    return array_get($this->attributes, 'inline');
  }
}