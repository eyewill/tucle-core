<?php namespace Eyewill\TucleCore\Contracts\Renderer;

interface FormElementRenderer
{
  public function setAttributes($attributes = []);
  public function text($options = []);
  public function select($options = []);
  public function textarea($options = []);
}