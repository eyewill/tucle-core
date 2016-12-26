<?php namespace Eyewill\TucleCore\Factories\Forms;

use Eyewill\TucleCore\Forms\FormStatic;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class StaticFactory extends Factory
{
  public function make(ModelPresenter $presenter)
  {
    return app()->make(FormStatic::class, [$presenter, $this]);
  }
}