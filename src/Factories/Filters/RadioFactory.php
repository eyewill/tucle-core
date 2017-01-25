<?php namespace Eyewill\TucleCore\Factories\Filters;

use Eyewill\TucleCore\Filters\FilterRadio;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class RadioFactory extends CheckboxFactory
{
  public function make(ModelPresenter $presenter)
  {
    return app()->make(FilterRadio::class, [$presenter, $this]);
  }

  public function isMulti()
  {
    return false;
  }

}