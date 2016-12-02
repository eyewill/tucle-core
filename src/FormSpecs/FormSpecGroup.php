<?php namespace Eyewill\TucleCore\FormSpecs;

use Eyewill\TucleCore\Factories\FormSpecFactory;
use Eyewill\TucleCore\Forms\FormGroup;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class FormSpecGroup extends FormSpec
{
  public function __construct($spec = [])
  {
    $attributes = [
      'forms' => array_get($spec, 'forms', []),
      'class' => array_get($spec, 'group', 'col-xs-12 group'),
    ];
    $attributes['name'] = array_pluck($attributes['forms'], 'name');

    parent::__construct($spec, $attributes);
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