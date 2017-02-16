<?php namespace Eyewill\TucleCore\Eloquent;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Batch
 * @package Eyewill\TucleCore\Eloquent
 */
trait Batch
{
  /**
   * @param array $entries
   * @return int
   */
  public static function batch($entries = [])
  {
    /** @var Model $model */

    $completes = 0;
    foreach ($entries as $entry)
    {
      $type = array_get($entry, 'type');
      $id = array_get($entry, 'id');
      if ($type == 'delete')
      {
        $model = static::find($id);
        if ($model->delete())
        {
          $completes++;
        }
      }
      elseif ($type == 'put')
      {
        $attributes = array_get($entry, 'attributes');
        $model = static::find($id);
        $model->fill($attributes);
        if ($model->save())
        {
          $completes++;
        }
      }
    }

    return $completes;
  }

}