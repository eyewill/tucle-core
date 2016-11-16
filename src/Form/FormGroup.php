<?php namespace Eyewill\TucleCore\Form;

use Eyewill\TucleCore\Factories\FormInputFactory;
use Eyewill\TucleCore\FormTypes\FormTypeGroup;

class FormGroup extends FormInput
{
  /** @var FormTypeGroup */
  protected $type;

  public function render()
  {
    $html = $this->label();

    $html.= '<div class="row">';
    $html.= $this->renderGroupForms();
    $html.= '</div>';

    return $html;
  }

  public function renderGroupForms()
  {
    $html = '';
    $forms = $this->type->getForms();
    foreach ($forms as $form)
    {
      $width = array_get($form, 'width', 'col-xs-12');
      $html.= '<div class="'.$width.'">';
      $form = FormInputFactory::make($this->presenter, $form);
      $html.= $form->render();
      $html.= '</div>';
    }

    return $html;
  }
}
