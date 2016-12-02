<?php namespace Eyewill\TucleCore\Forms;

use Eyewill\TucleCore\Factories\FormSpecFactory;
use Eyewill\TucleCore\FormSpecs\FormSpecGroup;

/**
 * Class FormGroup
 * @package Eyewill\TucleCore\Forms
 * @property FormSpecGroup $spec
 */
class FormGroup extends FormInput
{
  public function render($model = null)
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
      $formSpec = FormSpecFactory::make($spec);
      $form = $formSpec->makeForm($this->presenter);
      $html.= $form->render();
    }

    return $html;
  }

}
