<?php namespace Eyewill\TucleCore\FormSpecs;

use Eyewill\TucleCore\Forms\FormSelect;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class FormSpecSelect extends FormSpec
{
  public function __construct($spec = [])
  {
    $attributes = [
      'empty_label' => array_get($spec, 'empty_label', false),
      'values' => array_get($spec, 'values'),
    ];

    parent::__construct($spec, $attributes);
  }

  public function make(ModelPresenter $presenter)
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