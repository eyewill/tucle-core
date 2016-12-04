<?php namespace Eyewill\TucleCore\Forms;

use Eyewill\TucleCore\Contracts\FormSpecContract;
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

  public function __construct(ModelPresenter $presenter, FormSpecContract $spec)
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
   * @param null $model
   * @param bool $row
   * @return string
   */
  public function render($model = null, $row = true)
  {
    $spec = $this->spec;

    $html = '';
    if ($row)
    {
      $html.= '<div class="row">';
      $html.= '<div class="'.$spec->getClass().'">';
    }

    $html.= $this->label();
    $html.= $this->renderComponent($model);
    $html.= $this->renderHelp();
    $html.= $this->renderError();

    if ($row)
    {
      $html.= '</div>';
      $html.= '</div>';
    }

    $html = $this->formGroup($html);

    return $html;
  }

  protected function renderComponent($model)
  {
    $spec = $this->spec;
    $name = $spec->getName();
    $attributes = $spec->getAttributes()->get();
    return $this->presenter->getForm()->text($name, null, $attributes)->toHtml();
  }

  /**
   * @param string $source
   * @return string
   */
  protected function formGroup($source = '')
  {
    $class = 'form-group';

    if ($this->hasError())
    {
      $class .= ' has-error';
    }
    $html = '';
    $html.= '<div class="'.$class.'">';
    $html.= $source;
    $html.= '</div>';

    return $html;
  }


  public function label()
  {
    $class = '';

    if ($this->spec->getRequired())
    {
      $class .= ' required';
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