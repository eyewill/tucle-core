<?php namespace Eyewill\TucleCore\FormSpecs;

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
          'attributes' => [
            'data-provider' => 'datetimepicker',
            'data-date-format' => 'YYYY/MM/DD HH:mm',
          ],
        ],
        [
          'name' => 'terminated_at',
          'label' => '公開終了日',
          'width' => 'col-sm-3',
          'attributes' => [
            'data-provider' => 'datetimepicker',
            'data-date-format' => 'YYYY/MM/DD HH:mm',
          ],
        ],
      ],
    ];

    parent::__construct($spec, $attributes);
  }

  /**
   * @return array
   */
  public function getForms()
  {
    return array_get($this->attributes, 'forms');
  }

}