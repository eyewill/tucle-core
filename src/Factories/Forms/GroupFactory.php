<?php namespace Eyewill\TucleCore\Factories\Forms;

use Eyewill\TucleCore\Forms\FormGroup;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class GroupFactory extends Factory
{
  public function __construct($attributes = [], $mergeAttributes = [])
  {
    array_set($attributes, 'forms',
      array_get($attributes, 'forms', []));
    array_set($attributes, 'name',
      array_pluck(array_get($attributes, 'forms', []), 'name'));

    parent::__construct($attributes, $mergeAttributes);
  }

  public function make(ModelPresenter $presenter)
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
      $type = array_get($spec, 'type', 'text');
      $factory = app()->make('form.'.$type, [$spec]);
      $attributeNames += $factory->getAttributeNames();
    }
    return $attributeNames;
  }

}