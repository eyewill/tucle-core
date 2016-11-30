<?php namespace Eyewill\TucleCore\FormSpecs;

use Eyewill\TucleCore\Forms\FormDatetime;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class FormSpecDatetime extends FormSpec
{
  public function __construct($spec = [])
  {
    $spec['attributes'] = array_merge([
      'data-provider' => 'datetimepicker',
      'data-date-format' => 'YYYY/MM/DD HH:mm',
    ], array_get($spec, 'attributes', []));

    parent::__construct($spec);
  }

  public function make(ModelPresenter $presenter)
  {
    return app()->make(FormDatetime::class, [$presenter, $this]);
  }

}