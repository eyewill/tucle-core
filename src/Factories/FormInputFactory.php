<?php namespace Eyewill\TucleCore\Factories;

use Eyewill\TucleCore\Forms\FormInput;
use Eyewill\TucleCore\FormSpecs\FormSpecText;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class FormInputFactory
{
  /**
   * @param ModelPresenter $presenter
   * @param $spec
   * @return FormInput
   */
  public static function make(ModelPresenter $presenter, $spec)
  {
    $type = array_get($spec, 'type', 'text');

    $class = 'Eyewill\\TucleCore\\FormSpecs\\FormSpec'.studly_case($type);
    if (class_exists($class))
    {
      $formSpec = app()->make($class, [$spec]);
    }
    else
    {
      $formSpec = new FormSpecText($spec);
    }

    return $formSpec->make($presenter);
  }
}