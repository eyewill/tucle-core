<?php namespace Eyewill\TucleCore\FormSpecs;

class FormSpecImage extends FormSpecFile
{
  public function __construct($attributes = [], $mergeAttributes = [])
  {
    array_set($attributes, 'attr.data-allowed-file-extensions',
      array_get($attributes, 'attr.data-allowed-file-extensions', '["jpg", "png", "gif"]'));

    parent::__construct($attributes, $mergeAttributes);
  }
}