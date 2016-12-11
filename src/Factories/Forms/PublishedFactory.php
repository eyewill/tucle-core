<?php namespace Eyewill\TucleCore\Factories\Forms;

class PublishedFactory extends GroupFactory
{
  public function __construct($attributes = [], $mergeAttributes = [])
  {
    $attributes = array_merge([
      'label' => '公開期間',
      'forms' => [
        [
          'name' => 'published_at',
          'label' => '公開開始日',
          'width' => 'col-sm-3',
          'type' => 'datetime',
          'attr' => [
            'data-clearable' => true,
          ],
        ],
        [
          'name' => 'terminated_at',
          'label' => '公開終了日',
          'width' => 'col-sm-3',
          'type' => 'datetime',
          'attr' => [
            'data-clearable' => true,
          ],
        ],
      ],
      'name' => ['published_at', 'terminated_at'],
      'position' => 'sub',
    ], $attributes);

    parent::__construct($attributes, $mergeAttributes);
  }
}