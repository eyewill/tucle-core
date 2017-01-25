<?php namespace Eyewill\TucleCore\Filters;

use Eyewill\TucleCore\Contracts\Filters\Factory;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

class FilterInput
{
  /** @var Factory */
  protected $factory;

  protected $attributes;
  protected $renderGroup;
  /** @var ModelPresenter */
  protected $presenter;

  public function __construct(ModelPresenter $presenter, Factory $factory)
  {
    $this->presenter = $presenter;
    $this->factory = $factory;
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
    $spec = $this->factory;

    $html = '';
    if ($row)
    {
      $html.= '<div class="row">';
      $html.= '<div class="'.$spec->getClass().'">';
    }

    $html.= $this->label();
    $html.= $this->renderComponent($model);

    if ($row)
    {
      $html.= '</div>';
      $html.= '</div>';
    }

    $html = $this->formGroup($html);

    return $html;
  }

  protected function addon($source)
  {
    $html = '';
    $prefix = $this->factory->getAttribute('prefix');
    $suffix = $this->factory->getAttribute('suffix');
    if (!empty($prefix) || !empty($suffix))
    {
      $html.= '<div class="input-group">';
      if (!empty($prefix))
      {
        $html.= '<span class="input-group-addon">';
        $html.= $prefix;
        $html.= '</span>';
      }
      $html.= $source;
      if (!empty($suffix))
      {
        $html.= '<span class="input-group-addon">';
        $html.= $suffix;
        $html.= '</span>';
      }
      $html.= '</div>';
    }
    else
    {
      $html.= $source;
    }

    return $html;
  }

  protected function renderComponent($model)
  {
    $factory = $this->factory;
    $name = $factory->getName();
    $attributes = $factory->getAttributes()->get();

    $component = $this->presenter->getForm()->text($name, null, $attributes)->toHtml();

    return $this->addon($component);
  }

  /**
   * @param string $source
   * @return string
   */
  protected function formGroup($source = '')
  {
    $class = 'form-group';

    $html = '';
    $html.= '<div class="'.$class.'">';
    $html.= $source;
    $html.= '</div>';

    return $html;
  }


  public function label()
  {

    $html = '';
    $html.= '<label class="control-label">';
    $html.= e($this->factory->getLabel());
    $html.= '</label>';

    return $html;
  }
}