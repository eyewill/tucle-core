<?php namespace Eyewill\TucleCore\FormSpecs;

use Eyewill\TucleCore\Forms\FormTextarea;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class FormSpecTextarea extends FormSpec
{
  public function makeForm(ModelPresenter $presenter)
  {
    return app()->make(FormTextarea::class, [$presenter, $this]);
  }
}