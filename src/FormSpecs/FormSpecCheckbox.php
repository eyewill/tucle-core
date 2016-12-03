<?php namespace Eyewill\TucleCore\FormSpecs;

use Eyewill\TucleCore\Forms\FormCheckbox;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class FormSpecCheckbox extends FormSpec
{
  public function __construct($attributes = [], $mergeAttributes = [])
  {
    array_set($attributes, 'name',
      array_pluck(array_get($attributes, 'checkboxes', []), 'name'));

    parent::__construct($attributes, $mergeAttributes);
  }

  public function makeForm(ModelPresenter $presenter)
  {
    return app()->make(FormCheckbox::class, [$presenter, $this]);
  }

  public function getCheckboxes()
  {
    return array_get($this->attributes, 'checkboxes');
  }
}