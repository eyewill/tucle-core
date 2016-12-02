<?php namespace Eyewill\TucleCore\FormSpecs;

use Eyewill\TucleCore\Forms\FormPublished;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class FormSpecPublished extends FormSpecGroup
{
  public function __construct($attributes = [], $mergeAttributes = [])
  {
    $attributes = array_merge([
      'forms' => [
        [
          'name' => 'published_at',
          'label' => '公開開始日',
          'width' => 'col-sm-3',
          'type' => 'datetime',
        ],
        [
          'name' => 'terminated_at',
          'label' => '公開終了日',
          'width' => 'col-sm-3',
          'type' => 'datetime',
        ],
      ],
      'name' => ['published_at', 'terminated_at'],
    ], $attributes);

    parent::__construct($attributes, $mergeAttributes);
  }
}