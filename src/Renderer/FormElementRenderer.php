<?php namespace Eyewill\TucleCore\Renderer;

use Collective\Html\FormBuilder;
use Collective\Html\HtmlBuilder;
use Eyewill\TucleCore\Contracts\Renderer\FormElementRenderer as FormElementRendererContracts;
use Illuminate\Support\ViewErrorBag;

class FormElementRenderer implements FormElementRendererContracts
{
  /** @var FormBuilder */
  protected $form;

  /** @var HtmlBuilder */
  protected $html;

  /** @var ViewErrorBag */
  protected $errors;

  protected $attributes;

  public function __construct(FormBuilder $form, HtmlBuilder $html)
  {
    $this->form = $form;
    $this->html = $html;
  }

  public function errors()
  {
    if (!$this->errors)
    {
      $this->errors = $this->form->getSessionStore()->get('errors', new ViewErrorBag());
    }

    return $this->errors;
  }

  public function group($options = [])
  {
    $class = 'form-group';
    if ($this->errors()->has($this->get('name')))
      $class .= ' has-error';
    $options = [
        'class' => $class,
      ] + $options;

    return '<div'.$this->html->attributes($options).'>';
  }

  public function groupClose()
  {
    return '</div>';
  }

  public function label($options = [])
  {
    $class = '';
    if ($this->get('required')) {
      $class .= 'required';
    }
    $options = [
        'class' => $class,
      ] + $options;

    return $this->form->label($this->get('label'), null, $options);
  }

  public function text($options = [])
  {
    $options = [
        'class' => 'form-control',
      ] + $options;
    return $this->form->text($this->get('name'), null, $options);
  }

  public function textarea($options = [])
  {
    $options = [
        'class' => 'form-control',
      ] + $options;
    return $this->form->textarea($this->get('name'), null, $options);
  }
  
  public function select($options = [])
  {
    $options = [
        'class' => 'form-control',
      ] + $options;
    $values = [];
    if (!is_null($this->get('empty_label')))
    {
      $values[''] = $this->get('empty_label');
    }
    $values+= $this->get('values');

    return $this->form->select($this->get('name'), $values, null, $options);
  }

  public function renderHelp()
  {
    if ($this->get('help'))
    {
      return $this->get('help');
    }
    return '';
  }

  public function renderError()
  {
    $html = '';
    if ($this->errors()->has($this->get('name')))
    {
      $html.= '<span class="help-block">';
      $html.= '<strong>'.e($this->errors()->first($this->get('name'))).'</strong>';
      $html.= '</span>';
    }

    return $html;
  }

  public function setAttributes($attributes = [])
  {
    $this->attributes = $attributes;
  }

  public function get($name, $default = null)
  {
    return array_get($this->attributes, $name, $default);
  }


  public function value()
  {
    $html = '';

    $html.= '<div class="form-control-static">';
    $html.= nl2br(e($this->form->getValueAttribute($this->get('name'))));
    $html.= '</div>';

    return $html;
  }
}