<?php namespace Eyewill\TucleCore\Factories\Forms;

use Eyewill\TucleCore\Contracts\Forms\Factory as FactoryContracts;
use Eyewill\TucleCore\Forms\Attributes;
use Eyewill\TucleCore\Forms\FormInput;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

abstract class Factory implements FactoryContracts
{
  protected $attributes;

  public function __construct($attributes = [], $mergeAttributes = [])
  {
    array_set($attributes, 'position', array_get($attributes, 'position', 'main'));
    array_set($attributes, 'class', array_get($attributes, 'class', 'col-xs-12'));
    array_set($attributes, 'attr.class', array_get($attributes, 'attr.class', 'form-control'));
    array_set($attributes, 'value', array_get($attributes, 'value', null));

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

  public function getDefaultValue(ModelPresenter $presenter, $model = null)
  {
    $name = $this->getName();
    $func = camel_case($name).'Value';
    $value = null;

    if (session()->hasOldInput($name))
    {
      return $value;
    }

    if (method_exists($presenter, $func))
    {
      $value = $presenter->$func($model);
    }
    elseif (is_null($model))
    {
      $value = array_get($this->attributes, 'value');
    }

    return $value;
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
   * @return boolean
   */
  public function getRequired()
  {
    return array_get($this->attributes, 'required');
  }

  /**
   * @return string
   */
  public function getLabel()
  {
    return array_get($this->attributes, 'label');
  }

  /**
   * @return string
   */
  public function getHelp()
  {
    return array_get($this->attributes, 'help');
  }

  /**
   * @return Attributes
   */
  public function getAttributes()
  {
    return new Attributes(array_get($this->attributes, 'attr', []));
  }

  /**
   * @return string
   */
  public function getClass()
  {
    return array_get($this->attributes, 'class');
  }

  public function isPosition($position)
  {
    return (array_get($this->attributes, 'position') == $position);
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