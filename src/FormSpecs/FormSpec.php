<?php namespace Eyewill\TucleCore\FormSpecs;

use Eyewill\TucleCore\Forms\FormAttributes;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

abstract class FormSpec
{
  protected $attributes;

  public function __construct($spec = [], $mergeAttributes = [])
  {
    $this->attributes = array_merge([
      'type'     => array_get($spec, 'type', 'text'),
      'name'     => array_get($spec, 'name', ''),
      'required' => array_get($spec, 'required', false),
      'label'    => array_get($spec, 'label', ''),
      'class'    => array_get($spec, 'class', 'col-xs-12'),
      'help'     => array_get($spec, 'help', ''),
      'group'    => array_get($spec, 'group', true),
      'attr'     => new FormAttributes(array_get($spec, 'attr', [])),
    ], $mergeAttributes);
  }

  public function make(ModelPresenter $presenter)
  {
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
    return array_get($this->attributes, 'attr');
  }

  /**
   * @return string
   */
  public function getClass()
  {
    return array_get($this->attributes, 'class');
  }
}