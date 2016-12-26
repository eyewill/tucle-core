<?php namespace Eyewill\TucleCore\Factories\Forms;

use Eyewill\TucleCore\Forms\FormDate;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class DateFactory extends Factory
{
  public function __construct($attributes = [], $mergeAttributes = [])
  {
    array_set($attributes, 'attr.data-provider',
      array_get($attributes, 'attr.data-provider', 'datetimepicker'));
    array_set($attributes, 'attr.data-date-format',
      array_get($attributes, 'attr.data-date-format', 'YYYY/MM/DD'));

    parent::__construct($attributes, $mergeAttributes);
  }

  public function make(ModelPresenter $presenter)
  {
    return app()->make(FormDate::class, [$presenter, $this]);
  }

}