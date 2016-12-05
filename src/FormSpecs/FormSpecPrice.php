<?php namespace Eyewill\TucleCore\FormSpecs;

use Eyewill\TucleCore\Forms\FormPrice;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class FormSpecPrice extends FormSpec
{
  public function makeForm(ModelPresenter $presenter)
  {
    return app()->make(FormPrice::class, [$presenter, $this]);
  }
}