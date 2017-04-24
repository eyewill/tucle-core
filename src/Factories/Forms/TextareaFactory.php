<?php namespace Eyewill\TucleCore\Factories\Forms;

use Eyewill\TucleCore\Forms\FormTextarea;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class TextareaFactory extends Factory
{
  public function __construct($attributes = [], $mergeAttributes = [])
  {
    array_set($attributes, 'attr.data-provider',
      array_get($attributes, 'attr.data-provider', 'ckeditor'));
    array_set($attributes, 'attr.data-wysiwyg',
      array_get($attributes, 'attr.data-wysiwyg', false));

    parent::__construct($attributes, $mergeAttributes);
  }

  public function make(ModelPresenter $presenter)
  {
    return app()->make(FormTextarea::class, [$presenter, $this]);
  }
}