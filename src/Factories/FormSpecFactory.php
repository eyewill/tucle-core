<?php namespace Eyewill\TucleCore\Factories;

use Eyewill\TucleCore\FormSpecs\FormSpec;
use Eyewill\TucleCore\FormSpecs\FormSpecText;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class FormSpecFactory
{
  /**
   * @param $spec
   * @return FormSpec
   */
  public static function make($spec)
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

    return $formSpec;
  }
}