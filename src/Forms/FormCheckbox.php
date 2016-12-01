<?php namespace Eyewill\TucleCore\Forms;

use Eyewill\TucleCore\FormSpecs\FormSpecCheckbox;

class FormCheckbox extends FormInput
{
  /** @var FormSpecCheckbox */
  protected $spec;

  public function render($model = null)
  {
    $label = $this->spec->getLabel();
    $class = $this->spec->getClass();

    $html = '';
    $html.= '<div class="'.$class.'">';
    if ($label) {
      $html.= '<label>'.$label.'</label>';
    }
    foreach ($this->spec->getCheckboxes() as $spec)
    {
      $html.= $this->renderCheckbox($spec);
    }
    $html.= $this->renderHelp();
    $html.= $this->renderError();
    $html.= '</div>';

    if ($this->spec->getGroup())
    {
      $html = $this->grouping($html);
    }

    return $html;
  }

  public function renderCheckbox($spec)
  {
    $name = array_get($spec, 'name');
    $label = array_get($spec, 'label');
    $html = '';
    $html.= '<div class="checkbox">';
    $html.= '<input type="hidden" name="'.$name.'" value="">';
    $html.= '<label>';
    $html.= $this->presenter->getForm()->checkbox($name, 1)->toHtml();
    $html.= $label;
    $html.= '</label>';
    $html.= '</div>';

    return $html;
  }
}