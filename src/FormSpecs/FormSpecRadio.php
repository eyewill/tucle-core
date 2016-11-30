<?php namespace Eyewill\TucleCore\FormSpecs;

use Eyewill\TucleCore\Forms\FormRadio;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class FormSpecRadio extends FormSpec
{
  public function make(ModelPresenter $presenter)
  {
    return app()->make(FormRadio::class, [$presenter, $this]);
  }
}