<?php namespace Eyewill\TucleCore\FormSpecs;

use Eyewill\TucleCore\Forms\FormImage;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class FormSpecImage extends FormSpec
{
  public function __construct($attributes = [], $mergeAttributes = [])
  {
    array_set($attributes, 'group', array_get($attributes, 'group', true));
    array_set($attributes, 'attr.class', array_get($attributes, 'attr.class', 'file-loading'));
    array_set($attributes, 'attr.data-allowed-file-extensions', array_get($attributes, 'attr.data-allowed-file-extensions', '["jpg", "png", "gif"]'));

    $this->attributes = array_merge_recursive($attributes, $mergeAttributes);

    parent::__construct($attributes, $mergeAttributes);
  }

  public function makeForm(ModelPresenter $presenter)
  {
    return app()->make(FormImage::class, [$presenter, $this]);
  }

}