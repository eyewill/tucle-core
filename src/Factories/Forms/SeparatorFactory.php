<?php namespace Eyewill\TucleCore\Factories\Forms;

use Eyewill\TucleCore\Forms\FormSeparator;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class SeparatorFactory extends Factory
{
  public function make(ModelPresenter $presenter)
  {
    return app()->make(FormSeparator::class, [$presenter, $this]);
  }
}