<?php namespace Eyewill\TucleCore\Factories\Forms;

use Eyewill\TucleCore\Forms\FormText;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class TextFactory extends Factory
{
  public function __construct($attributes = [], $mergeAttributes = [])
  {
    array_set($attributes, 'prefix', array_get($attributes, 'prefix', null));
    array_set($attributes, 'suffix', array_get($attributes, 'suffix', null));

    parent::__construct($attributes, $mergeAttributes);
  }

  public function make(ModelPresenter $presenter)
  {
    return app()->make(FormText::class, [$presenter, $this]);
  }
}