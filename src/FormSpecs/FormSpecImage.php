<?php namespace Eyewill\TucleCore\FormSpecs;

use Eyewill\TucleCore\Forms\FormImage;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class FormSpecImage extends FormSpec
{
  public function makeForm(ModelPresenter $presenter)
  {
    return app()->make(FormImage::class, [$presenter, $this]);
  }

}