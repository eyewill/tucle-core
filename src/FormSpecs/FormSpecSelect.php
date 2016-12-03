<?php namespace Eyewill\TucleCore\FormSpecs;

use Eyewill\TucleCore\Forms\FormSelect;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class FormSpecSelect extends FormSpec
{
  public function __construct($attributes = [], $mergeAttributes = [])
  {
    array_set($attributes, 'empty_label',
      array_get($attributes, 'empty_label', false));
    array_set($attributes, 'values',
      array_get($attributes, 'values', []));

    parent::__construct($attributes, $mergeAttributes);
  }

  public function makeForm(ModelPresenter $presenter)
  {
    return app()->make(FormSelect::class, [$presenter, $this]);
  }

  public function getEmptyLabel()
  {
    return array_get($this->attributes, 'empty_label');
  }

  public function getValues()
  {
    return array_get($this->attributes, 'values');
  }
}