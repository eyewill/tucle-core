<?php namespace Eyewill\TucleCore\FormSpecs;

use Eyewill\TucleCore\Forms\FormRadio;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class FormSpecRadio extends FormSpecCheckbox
{
  public function makeForm(ModelPresenter $presenter)
  {
    $this->setValues($presenter);
    return app()->make(FormRadio::class, [$presenter, $this]);
  }
}