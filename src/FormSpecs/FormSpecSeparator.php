<?php namespace Eyewill\TucleCore\FormSpecs;

use Eyewill\TucleCore\Forms\FormSeparator;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class FormSpecSeparator extends FormSpec
{
  public function makeForm(ModelPresenter $presenter)
  {
    return app()->make(FormSeparator::class, [$presenter, $this]);
  }
}