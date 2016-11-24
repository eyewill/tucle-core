<?php namespace Eyewill\TucleCore\Form;

use Eyewill\TucleCore\FormSpecs\FormSpec;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

class FormInput
{
  /** @var  FormSpec */
  protected $spec;

  protected $name;
  protected $attributes;
  protected $renderGroup;
  /** @var ModelPresenter */
  protected $presenter;

  public function __construct(ModelPresenter $presenter, FormSpec $type)
  {
    $this->presenter = $presenter;
    $this->spec = $type;
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
    $attributes = $type->getAttributes()->merge([
      'class' => 'form-control',
    ])->get();

    $html = $this->label();
    $html.= $this->presenter->getForm()->text($name, null, $attributes)->toHtml();
    $html.= $this->renderHelp();
    $html.= $this->renderError();
    if ($this->spec->getGroup())
    {
      $html = $this->grouping($html);
    }

    return $html;
  }

  /**
   * @param string $html
   * @return string
   */
  protected function grouping($html = '')
  {
    $class = 'form-group';

    if ($this->errors()->has($this->name))
    {
      $class .= ' has-error';
    }

    $attributes = [
        'class' => $class,
      ];

    return '<div'.$this->presenter->getHtml()->attributes($attributes).'>'.$html.'</div>';
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

    return $this->errors()->has($name);
  }

  public function renderError()
  {
    $name = $this->spec->getName();

    $html = '';
    if ($this->hasError())
    {
      $html.= '<p class="help-block">';
      $html.= '<strong>'.e($this->errors()->first($name)).'</strong>';
      $html.= '</p>';
    }

    return $html;
  }

}