<?php namespace Eyewill\TucleCore\Factories\Forms;

use Eyewill\TucleCore\Forms\FormPrice;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class PriceFactory extends TextFactory
{
  public function __construct($attributes = [], $mergeAttributes = [])
  {
    array_set($attributes, 'suffix', array_get($attributes, 'suffix', '円'));

    parent::__construct($attributes, $mergeAttributes);
  }

  public function make(ModelPresenter $presenter)
  {
    return app()->make(FormPrice::class, [$presenter, $this]);
  }
}