<?php namespace Eyewill\TucleCore\Factories\Forms;

use Eyewill\TucleCore\Forms\FormPrice;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class PriceFactory extends Factory
{
  public function make(ModelPresenter $presenter)
  {
    return app()->make(FormPrice::class, [$presenter, $this]);
  }
}