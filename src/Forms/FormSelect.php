<?php namespace Eyewill\TucleCore\Forms;

use Eyewill\TucleCore\FormSpecs\FormSpec;
use Eyewill\TucleCore\FormSpecs\FormSpecSelect;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;
use Illuminate\Support\Collection;

/**
 * Class FormSelect
 * @package Eyewill\TucleCore\Forms
 * @property FormSpecSelect $spec
 */
class FormSelect extends FormInput
{
  protected $emptyLabel;
  protected $values = [];

  public function __construct(ModelPresenter $presenter, FormSpec $spec)
  {
    $values = $spec->getValues();
    if (!is_array($values))
    {
      $func = camel_case($spec->getName()).'Values';
      if (is_callable([$presenter, $func]))
      {
        $values = $presenter->{$func}();
        if ($values instanceof Collection)
        {
          $values = $values->toArray();
        }
      }
      else
      {
        $values = [];
      }
    }
    $this->setValues($values);
    $this->setEmptyLabel($spec->getEmptyLabel());
    parent::__construct($presenter, $spec);
  }

  public function setValues($values = [])
  {
    $this->values = $values;
  }

  public function setEmptyLabel($emptyLabel)
  {
    $this->emptyLabel = $emptyLabel;
  }

  public function render($model = null)
  {
    $spec = $this->spec;
    $name = $spec->getName();
    $class = $spec->getClass();
    $attributes = $spec->getAttributes()->merge([
      'class' => 'form-control',
    ])->get();
    $values = $this->values;
    if ($this->emptyLabel)
    {
      $values = ['' => $this->emptyLabel]+$values;
    }

    $html = '';

    $html.= '<div class="'.$class.'">';
    $html.= $this->label();
    $html.= $this->presenter->getForm()->select($name, $values, null, $attributes)->toHtml();
    $html.= $this->renderHelp();
    $html.= $this->renderError();
    $html.= '</div>';

    if ($spec->getGroup())
    {
      $html = $this->grouping($html);
    }

    return $html;
  }
}
