<?php namespace Eyewill\TucleCore\Forms;

use Eyewill\TucleCore\Factories\Forms\GroupFactory;

/**
 * Class FormGroup
 * @package Eyewill\TucleCore\Forms
 * 
 * @property GroupFactory $factory
 */
class FormGroup extends FormInput
{
  public function render($model = null, $row = true)
  {
    $spec = $this->factory;

    $html = '';
    $html.= $this->label($model);
    if ($row)
    {
      $html.= '<div class="row">';
      $html.= '<div class="'.$spec->getClass().'">';
    }

    $html.= $this->renderComponent($model);
    $html.= $this->renderHelp($model);

    if ($row)
    {
      $html.= '</div>';
      $html.= '</div>';
    }

    $html = $this->formGroup($html);

    return $html;
  }

  public function renderComponent($model = null)
  {
    $html = '';
    $forms = $this->factory->getForms();
    $html.= '<div class="row">';
    foreach ($forms as $spec)
    {
      $exists = array_get($spec, 'exists', false);
      if ($exists && is_null($model))
      {
        continue;
      }
      $creates = array_get($spec, 'creates', false);
      if ($creates && !is_null($model))
      {
        continue;
      }
      $name = array_get($spec, 'name');
      if (!$this->presenter->renderWhen($name, $model))
      {
        continue;
      }
      $type = array_get($spec, 'type', 'text');
      $class = array_get($spec, 'class', 'col-xs-12');
      $factory = app()->make('form.'.$type, [$spec]);
      $form = $factory->make($this->presenter);
      $html.= '<div class="'.$class.'">';
      $html.= $form->render($model, false);
      $html.= '</div>';
     }
     $html.= '</div>';

    return $html;
  }

  public function formGroup($source = '')
  {
    $class = 'form-group group';

    $html = '';
    $html .= '<div class="' . $class . '">';
    $html .= $source;
    $html .= '</div>';

    return $html;
  }
}
