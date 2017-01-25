<?php namespace Eyewill\TucleCore\Factories\Filters;

use Eyewill\TucleCore\Contracts\Filters\Factory as FactoryContracts;
use Eyewill\TucleCore\Forms\FormInput;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

abstract class Factory implements FactoryContracts
{
  protected $attributes;

  public function __construct($attributes = [], $mergeAttributes = [])
  {
    array_set($attributes, 'class', array_get($attributes, 'class', 'col-xs-12'));
    array_set($attributes, 'attr.class', array_get($attributes, 'attr.class', 'form-control'));

    $this->attributes = array_merge_recursive($attributes, $mergeAttributes);
  }

  /**
   * @param ModelPresenter $presenter
   * @return FormInput
   */
  public function make(ModelPresenter $presenter)
  {
    return new FormInput($presenter, $this);
  }

  public function getAttributeNames()
  {
    return [$this->getName() => $this->getLabel()];
  }

  /**
   * @return string
   */
  public function getName()
  {
    return array_get($this->attributes, 'name');
  }

  /**
   * @return string
   */
  public function getLabel()
  {
    return array_get($this->attributes, 'label');
  }

  /**
   * @return Attributes
   */
  public function getAttributes()
  {
    return $this->attributes;
  }

  /**
   * @return string
   */
  public function getClass()
  {
    return array_get($this->attributes, 'class');
  }

  public function hasAttribute($name)
  {
    return array_has($this->attributes, $name);
  }

  public function getAttribute($name)
  {
    return array_get($this->attributes, $name);
  }
}