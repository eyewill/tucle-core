<?php namespace Eyewill\TucleCore\FormSpecs;

use Eyewill\TucleCore\Forms\FormDate;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class FormSpecDate extends FormSpec
{
  public function __construct($attributes = [], $mergeAttributes = [])
  {
    array_set($attributes, 'attr.data-provider',
      array_get($attributes, 'attr.data-provider', 'datetimepicker'));
    array_set($attributes, 'attr.data-date-format',
      array_get($attributes, 'attr.data-date-format', 'YYYY/MM/DD'));
    array_set($attributes, 'attr.data-date-disabled-hours',
      array_get($attributes, 'attr.data-date-disabled-hours', '[true]'));

    parent::__construct($attributes, $mergeAttributes);
  }

  public function makeForm(ModelPresenter $presenter)
  {
    return app()->make(FormDate::class, [$presenter, $this]);
  }

}