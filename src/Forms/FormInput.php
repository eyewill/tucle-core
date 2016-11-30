<?php namespace Eyewill\TucleCore\Forms;

use Eyewill\TucleCore\FormSpecs\FormSpec;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

class FormInput
{
  /** @var FormSpec */
  protected $spec;

  protected $attributes;
  protected $renderGroup;
  /** @var ModelPresenter */
  protected $presenter;

  public function __construct(ModelPresenter $presenter, FormSpec $spec)
  {
    $this->presenter = $presenter;
    $this->spec = $spec;
  }

  /**
   * @return ViewErrorBag|MessageBag
   */
  public function errors()
  {
    return $this->presenter->getForm()->getSessionStore()->get('errors', new MessageBag);
  }

  /**
   * @param bool $renderGroup
   */
  public function setRenderGroup($renderGroup = true)
  {
    $this->renderGroup = $renderGroup;
  }

  /**
   * @return string
   */
  public function render()
  {
    $type = $this->spec;
    $name = $type->getName();
    $width = $type->getWidth();
    $attributes = $type->getAttributes()->merge([
      'class' => 'form-control',
    ])->get();

    $html = '';
    $html.= '<div class="'.$width.'">';
    $html.= $this->label();
    $html.= $this->presenter->getForm()->text($name, null, $attributes)->toHtml();
    $html.= $this->renderHelp();
    $html.= $this->renderError();
    $html.= '</div>';

    if ($this->spec->getGroup())
    {
      $html = $this->grouping($html);
    }

    return $html;
  }

  /**
   * @param string $source
   * @return string
   */
  protected function grouping($source = '')
  {
    $class = 'form-group';

    if ($this->hasError())
    {
      $class .= ' has-error';
    }

    $attributes = [
        'class' => $class,
      ];

    $html = '';
    $html.= '<div'.$this->presenter->getHtml()->attributes($attributes).'>';
    $html.= '<div class="row">';
    $html.= $source;
    $html.= '</div>';
    $html.= '</div>';

    return $html;
  }


  public function label()
  {
    $class = '';

    if ($this->spec->getRequired())
    {
      $class .= 'required';
    }

    $attributes = [
      'class' => $class,
    ];
    return $this->presenter->getForm()->label($this->spec->getLabel(), null, $attributes);
  }

  public function renderHelp()
  {
    $help = $this->spec->getHelp();

    $html = '';
    if (!empty($help))
    {
      $html.= '<p class="help-block">';
      $html.= e($help);
      $html.= '</p>';
    }

    return $html;
  }

  public function hasError()
  {
    $name = $this->spec->getName();
    return $this->errors()->hasAny(is_array($name) ? $name : [$name]);
  }

  public function renderError()
  {
    $name = $this->spec->getName();

    $html = '';
    if ($this->hasError())
    {
      foreach (is_array($name) ? $name : [$name] as $key)
      {
        $html.= '<p class="help-block">';
        $html.= '<strong>'.e($this->errors()->first($key)).'</strong>';
        $html.= '</p>';
      }
    }

    return $html;
  }

}