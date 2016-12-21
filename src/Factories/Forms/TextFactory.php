<?php namespace Eyewill\TucleCore\Factories\Forms;

use Eyewill\TucleCore\Forms\FormText;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class TextFactory extends Factory
{
  public function make(ModelPresenter $presenter)
  {
    $this->setValue($presenter);
    return app()->make(FormText::class, [$presenter, $this]);
  }
}