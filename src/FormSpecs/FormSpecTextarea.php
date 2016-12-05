<?php namespace Eyewill\TucleCore\FormSpecs;

use Eyewill\TucleCore\Forms\FormTextarea;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class FormSpecTextarea extends FormSpec
{
  public function __construct($attributes = [], $mergeAttributes = [])
  {
    array_set($attributes, 'attr.data-provider',
      array_get($attributes, 'attr.data-provider', 'ckeditor'));
    array_set($attributes, 'attr.data-wysiwyg',
      array_get($attributes, 'attr.data-wysiwyg', 'true'));

    parent::__construct($attributes, $mergeAttributes);
  }

  public function makeForm(ModelPresenter $presenter)
  {
    return app()->make(FormTextarea::class, [$presenter, $this]);
  }
}