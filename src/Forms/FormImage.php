<?php namespace Eyewill\TucleCore\Forms;

use Eyewill\TucleCore\FormSpecs\FormSpecImage;

class FormImage extends FormInput
{
  public function render()
  {
    /** @var FormSpecImage $spec */
    $spec = $this->spec;
    $name = $spec->getName();
    $width = $spec->getWidth();
    $attributes = $spec->getAttributes()->merge([
      'class' => 'file-loading',
      'data-allowed-file-extensions' => '["jpg", "png", "gif"]'
    ])->get();

    $html = '';
    $html.= '<div class="'.$width.'">';
    $html.= $this->label();
    $html.= $this->presenter->getForm()->file($name, $attributes)->toHtml();
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