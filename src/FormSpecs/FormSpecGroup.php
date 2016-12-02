<?php namespace Eyewill\TucleCore\FormSpecs;

use Eyewill\TucleCore\Factories\FormSpecFactory;
use Eyewill\TucleCore\Forms\FormGroup;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class FormSpecGroup extends FormSpec
{
  public function __construct($attributes = [], $mergeAttributes = [])
  {
    array_set($attributes, 'forms', array_get($attributes, 'forms', []));
    array_set($attributes, 'name', array_pluck(array_get($attributes, 'forms', []), 'name'));

    parent::__construct($attributes, $mergeAttributes);
  }

  public function makeForm(ModelPresenter $presenter)
  {
    return app()->make(FormGroup::class, [$presenter, $this]);
  }

  /**
   * @return array
   */
  public function getForms()
  {
    return array_get($this->attributes, 'forms');
  }

  public function getAttributeNames()
  {
    $attributeNames = [];
    foreach ($this->getForms() as $spec)
    {
      $formSpec = FormSpecFactory::make($spec);
      $attributeNames += $formSpec->getAttributeNames();
    }
    return $attributeNames;
  }

}