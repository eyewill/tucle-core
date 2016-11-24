<?php namespace Eyewill\TucleCore\FormSpecs;

use Eyewill\TucleCore\Forms\FormText;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class FormSpecText extends FormSpec
{
  public function make(ModelPresenter $presenter)
  {
    return app()->make(FormText::class, [$presenter, $this]);
  }
}