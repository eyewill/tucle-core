<?php namespace Eyewill\TucleCore\Form;

use Eyewill\TucleCore\FormSpecs\FormSpec;
use Eyewill\TucleCore\FormSpecs\FormSpecSelect;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class FormSelect extends FormInput
{
  protected $emptyLabel;
  protected $values = [];

  public function __construct(ModelPresenter $presenter, FormSpec $type)
  {
    $this->setValues($type->getValues());
    $this->setEmptyLabel($type->getEmptyLabel());
    parent::__construct($presenter, $type);
  }

  public function setValues($values = [])
  {
    $this->values = $values;
  }

  public function setEmptyLabel($emptyLabel)
  {
    $this->emptyLabel = $emptyLabel;
  }

  public function render()
  {
    /** @var FormSpecSelect $type */
    $type = $this->spec;
    $name = $type->getName();
    $attributes = $type->getAttributes()->merge([
      'class' => 'form-control',
    ])->get();
    $values = $this->values;
    if ($this->emptyLabel)
    {
      $values = ['' => $this->emptyLabel]+$values;
    }

    $html = $this->label();
    $html.= $this->presenter->getForm()->select($name, $values, null, $attributes)->toHtml();
    $html.= $this->renderHelp();
    $html.= $this->renderError();
    if ($type->getGroup())
    {
      $html = $this->grouping($html);
    }

    return $html;
  }
}
