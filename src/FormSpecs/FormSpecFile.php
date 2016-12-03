<?php namespace Eyewill\TucleCore\FormSpecs;

use Eyewill\TucleCore\Forms\FormFile;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class FormSpecFile extends FormSpec
{
  public function __construct($attributes = [], $mergeAttributes = [])
  {
    array_set($attributes, 'group',
      array_get($attributes, 'group', true));
    array_set($attributes, 'attr.class',
      array_get($attributes, 'attr.class', 'file-loading'));
    array_set($attributes, 'attr.data-allowed-file-extensions',
      array_get($attributes, 'attr.data-allowed-file-extensions', '["pdf", "xls", "xlsx", "csv"]'));

    parent::__construct($attributes, $mergeAttributes);
  }

  public function makeForm(ModelPresenter $presenter)
  {
    return app()->make(FormFile::class, [$presenter, $this]);
  }

}