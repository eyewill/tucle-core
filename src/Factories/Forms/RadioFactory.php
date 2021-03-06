<?php namespace Eyewill\TucleCore\Factories\Forms;

use Eyewill\TucleCore\Forms\FormRadio;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class RadioFactory extends CheckboxFactory
{
  public function make(ModelPresenter $presenter)
  {
    return app()->make(FormRadio::class, [$presenter, $this]);
  }
}