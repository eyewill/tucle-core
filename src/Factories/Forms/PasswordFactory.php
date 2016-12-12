<?php namespace Eyewill\TucleCore\Factories\Forms;

use Eyewill\TucleCore\Forms\FormPassword;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class PasswordFactory extends Factory
{
  public function make(ModelPresenter $presenter)
  {
    return app()->make(FormPassword::class, [$presenter, $this]);
  }
}