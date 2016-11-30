<?php namespace Eyewill\TucleCore\FormSpecs;

use Eyewill\TucleCore\Forms\FormPublished;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class FormSpecPublished extends FormSpec
{
  public function __construct($spec = [])
  {
    $attributes = [
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
    ];

    parent::__construct($spec, $attributes);
  }

  public function make(ModelPresenter $presenter)
  {
    return app()->make(FormPublished::class, [$presenter, $this]);
  }

  /**
   * @return array
   */
  public function getForms()
  {
    return array_get($this->attributes, 'forms');
  }

}