<?php namespace Eyewill\TucleCore\Form;

use Eyewill\TucleCore\FormTypes\FormType;
use Eyewill\TucleCore\FormTypes\FormTypeSelect;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class FormSelect extends FormInput
{
  protected $emptyLabel;
  protected $values = [];

  public function __construct(ModelPresenter $presenter, FormType $type)
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
    /** @var FormTypeSelect $type */
    $type = $this->type;
    $name = $type->getName();
    $attributes = $type->getAttributes()->mergeAttributes([
      'class' => 'form-control',
    ]);
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
