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
  public function render($model = null, $row = true)
  {
    $spec = $this->spec;

    $html = '';
    $html.= $this->label();
    if ($row)
    {
      $html.= '<div class="row">';
      $html.= '<div class="'.$spec->getClass().'">';
    }

    $html.= $this->renderComponent($model);
    $html.= $this->renderHelp();

    if ($row)
    {
      $html.= '</div>';
      $html.= '</div>';
    }

    $html = $this->formGroup($html);

    return $html;
  }

  public function renderComponent($model)
  {
    $html = '';
    $forms = $this->spec->getForms();
    $html.= '<div class="row">';
    foreach ($forms as $spec)
    {
      $class = array_get($spec, 'class', 'col-xs-12');
      $formSpec = FormSpecFactory::make($spec);
      $form = $formSpec->makeForm($this->presenter);
      $html.= '<div class="'.$class.'">';
      $html.= $form->render($model, false);
      $html.= '</div>';
     }
     $html.= '</div>';

    return $html;
  }

  protected function formGroup($source = '')
  {
    $class = 'form-group group';

    $html = '';
    $html .= '<div class="' . $class . '">';
    $html .= $source;
    $html .= '</div>';

    return $html;
  }
}
