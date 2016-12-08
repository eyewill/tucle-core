<?php namespace Eyewill\TucleCore\Eloquent;

/**
 * Class Batch
 * @package Eyewill\TucleCore\Eloquent
 */
trait Batch
{
  public static function batch($entries = [])
  {
    foreach ($entries as $entry)
    {
      $type = array_get($entry, 'type');
      $id = array_get($entry, 'id');
      if ($type == 'delete')
      {
        $model = static::find($id);
        $model->delete();
      }
      elseif ($type == 'put')
      {
        $attributes = array_get($entry, 'attributes');
        $model = static::find($id);
        $model->fill($attributes);
        $model->save();
      }
    }
  }

}