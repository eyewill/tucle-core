<?php namespace Eyewill\TucleCore\Forms;

class FormCustom extends FormInput
{
  public function render($model = null, $row = true)
  {
    $func = 'render'.studly_case($this->factory->getName());
    if (method_exists($this->presenter, $func))
    {
      return $this->presenter->$func($this, $model);
    }

    return parent::render($model, $row);
  }

  public function renderComponent($model = null)
  {
    $func = 'render'.studly_case($this->factory->getName()).'Component';
    if (method_exists($this->presenter, $func))
    {
      return $this->presenter->$func($this, $model);
    }

    return parent::renderComponent($model);
  }

  public function renderHelp($model = null)
  {
    $func = 'render'.studly_case($this->factory->getName()).'Help';
    if (method_exists($this->presenter, $func))
    {
      return $this->presenter->$func($this, $model);
    }

    return parent::renderHelp($model);
  }
}