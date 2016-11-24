<?php namespace Eyewill\TucleCore\Forms;

class FormSpec
{
  protected $type;
  protected $name;
  protected $required;
  protected $label;
  protected $help;
  protected $renderGroup;

  /** @var  FormSpecAttributes */
  protected $attributes;

  public function __construct($spec = [])
  {
    $type = array_get($spec, 'type', 'text');
    $name = array_get($spec, 'name');
    $required = array_get($spec, 'required', false);
    $label = array_get($spec, 'label');
    $help = array_get($spec, 'help');
    $group = array_get($spec, 'group', true);
    $attributes = array_get($spec, 'attributes', []);
    $this->setName($name);
    $this->setType($type);
    $this->setRequired($required);
    $this->setLabel($label);
    $this->setHelp($help);
    $this->setRenderGroup($group);
    $this->setFormSpecAttributes(new FormSpecAttributes($attributes));
  }

  public function setType($type)
  {
    $this->type = $type;
  }

  public function getType()
  {
    return $this->type;
  }

  /**
   * @param string $name
   */
  public function setName($name)
  {
    $this->name = $name;
  }

  public function getName()
  {
    return $this->name;
  }

  /**
   * @param bool $required
   */
  public function setRequired($required = true)
  {
    $this->required = $required;
  }

  public function getRequired()
  {
    return $this->required;
  }

  /**
   * @param string $label
   */
  public function setLabel($label)
  {
    $this->label = $label;
  }

  public function getLabel()
  {
    return $this->label;
  }

  /**
   * @param string $help
   */
  public function setHelp($help = '')
  {
    $this->help = $help;
  }

  public function getHelp()
  {
    return $this->help;
  }

  public function setFormSpecAttributes(FormSpecAttributes $attributes)
  {
    $this->attributes = $attributes;
  }

  public function getFormSpecAttributes()
  {
    return $this->attributes;
  }

  public function setRenderGroup($group = true)
  {
    $this->renderGroup = $group;
  }

  public function getRenderGroup()
  {
    return $this->renderGroup;
  }
}