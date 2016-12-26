<?php namespace Eyewill\TucleCore\Factories\Forms;

use Eyewill\TucleCore\Forms\FormRadio;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class RadioFactory extends CheckboxFactory
{
  public function make(ModelPresenter $presenter)
  {
    $this->setCheckedValues($presenter);
    $this->setValues($presenter);
    return app()->make(FormRadio::class, [$presenter, $this]);
  }
}