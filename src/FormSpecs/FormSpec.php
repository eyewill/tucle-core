<?php namespace Eyewill\TucleCore\FormSpecs;

use Eyewill\TucleCore\Forms\FormAttributes;
use Eyewill\TucleCore\Forms\FormInput;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

abstract class FormSpec
{
  protected $attributes;

  public function __construct($attributes = [], $mergeAttributes = [])
  {
    array_set($attributes, 'group', array_get($attributes, 'group', true));
    array_set($attributes, 'class', array_get($attributes, 'class', 'col-xs-12'));
    array_set($attributes, 'attr.class', array_get($attributes, 'attr.class', 'form-control'));

    $this->attributes = array_merge_recursive($attributes, $mergeAttributes);
  }

  /**
   * @param ModelPresenter $presenter
   * @return FormInput
   */
  public function makeForm(ModelPresenter $presenter)
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
  public function getGroup()
  {
    return array_get($this->attributes, 'group');
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
   * @return FormAttributes
   */
  public function getAttributes()
  {
    return new FormAttributes(array_get($this->attributes, 'attr', []));
  }

  /**
   * @return string
   */
  public function getClass()
  {
    return array_get($this->attributes, 'class');
  }
}