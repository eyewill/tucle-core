<?php namespace Eyewill\TucleCore\Form;

use Eyewill\TucleCore\FormTypes\FormType;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

class FormInput
{
  /** @var  FormType */
  protected $type;

  protected $name;
  protected $attributes;
  protected $renderGroup;
  /** @var ModelPresenter */
  protected $presenter;

  public function __construct(ModelPresenter $presenter, FormType $type)
  {
    $this->presenter = $presenter;
    $this->type = $type;
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
    $type = $this->type;
    $name = $type->getName();
    $attributes = $type->getAttributes()->mergeAttributes([
      'class' => 'form-control',
    ]);

    $html = $this->label();
    $html.= $this->presenter->getForm()->text($name, null, $attributes)->toHtml();
    $html.= $this->renderHelp();
    $html.= $this->renderError();
    if ($this->type->getGroup())
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

    if ($this->type->getRequired())
    {
      $class .= 'required';
    }

    $attributes = [
      'class' => $class,
    ];
    return $this->presenter->getForm()->label($this->type->getLabel(), null, $attributes);
  }

  public function renderHelp()
  {
    $help = $this->type->getHelp();

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
    $name = $this->type->getName();

    return $this->errors()->has($name);
  }

  public function renderError()
  {
    $name = $this->type->getName();

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