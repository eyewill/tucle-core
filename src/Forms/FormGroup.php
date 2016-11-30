<?php namespace Eyewill\TucleCore\Forms;

use Eyewill\TucleCore\Factories\FormInputFactory;
use Eyewill\TucleCore\FormSpecs\FormSpecGroup;

class FormGroup extends FormInput
{
  /** @var FormSpecGroup */
  protected $spec;

  public function render()
  {
    $label = $this->spec->getLabel();
    $class = $this->spec->getClass();

    $html = '';
    $html.= '<div class="'.$class.'">';
    if ($label) {
      $html.= '<label>'.$label.'</label>';
    }
    $html.= '<div class="row">';
    $html.= $this->renderGroupForms();
    $html.= '</div>';
    $html.= '</div>';

    $html = $this->grouping($html);

    return $html;
  }

  public function renderGroupForms()
  {
    $html = '';
    $forms = $this->spec->getForms();
    foreach ($forms as $spec)
    {
      $spec['group'] = false;
      $form = FormInputFactory::make($this->presenter, $spec);
      $html.= $form->render();
    }

    return $html;
  }

}
