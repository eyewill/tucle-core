<?php namespace Eyewill\TucleCore\FormSpecs;

use Eyewill\TucleCore\Forms\FormCheckbox;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class FormSpecCheckbox extends FormSpec
{
  public function __construct($spec = [])
  {
    $attributes = [
      'checkboxes' => array_get($spec, 'checkboxes', []),
    ];
    $attributes['name'] = array_pluck($attributes['checkboxes'], 'name');

    parent::__construct($spec, $attributes);
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