<?php namespace Eyewill\TucleCore\Factories\Forms;

use Eyewill\TucleCore\Forms\FormCustom;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class CustomFactory extends Factory
{
  public function make(ModelPresenter $presenter)
  {
    return app()->make(FormCustom::class, [$presenter, $this]);
  }
}