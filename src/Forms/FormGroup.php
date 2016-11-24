<?php namespace Eyewill\TucleCore\Forms;

use Eyewill\TucleCore\Factories\FormInputFactory;
use Eyewill\TucleCore\FormSpecs\FormSpecGroup;

class FormGroup extends FormInput
{
  /** @var FormSpecGroup */
  protected $spec;

  public function render()
  {
    $html = '';
    $html.= '<div class="col-xs-12">';
    $html.= $this->label();
    $html.= '</div>';
    $html.= $this->renderGroupForms();

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
