<?php namespace Eyewill\TucleCore\FormSpecs;

use Eyewill\TucleCore\Forms\FormDatetime;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class FormSpecDatetime extends FormSpec
{
  public function __construct($spec = [])
  {
    $spec['attr'] = array_merge([
      'data-provider' => 'datetimepicker',
      'data-date-format' => 'YYYY/MM/DD HH:mm',
    ], array_get($spec, 'attr', []));

    parent::__construct($spec);
  }

  public function makeForm(ModelPresenter $presenter)
  {
    return app()->make(FormDatetime::class, [$presenter, $this]);
  }

}