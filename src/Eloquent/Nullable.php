<?php namespace Eyewill\TucleCore\Eloquent;

trait Nullable
{
  protected static function bootNullable()
  {
    static::saving(function($model)
    {
      self::setNullables($model);
    });
  }

  /**
   * Set empty nullable fields to null
   * @param object $model
   */
  protected static function setNullables($model)
  {
    foreach($model->nullable as $field)
    {
      if(empty($model->{$field}))
      {
        $model->{$field} = null;
      }
    }
  }
}