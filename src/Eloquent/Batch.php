<?php namespace Eyewill\TucleCore\Eloquent;

use Exception;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Batch
 * @package Eyewill\TucleCore\Eloquent
 */
trait Batch
{
  /**
   * @param array $entries
   * @param bool $force
   * @return int
   */
  public static function batch($entries = [], $force = false)
  {
    $completes = 0;

    app()->make('db')->transaction(function () use ($entries, $completes, $force) {

      /** @var Model $model */

      foreach ($entries as $entry)
      {
        $type = array_get($entry, 'type');
        $id = array_get($entry, 'id');
        if ($type == 'delete')
        {
          $model = static::find($id);
          if (!$model->delete())
          {
            if (!$force)
              throw new Exception('deleting '.$model->getTables().' failure.');
          }
          else
          {
            $completes++;
          }
        }
        elseif ($type == 'put')
        {
          $attributes = array_get($entry, 'attributes');
          $model = static::find($id);
          $model->fill($attributes);
          if (!$model->save())
          {
            if (!$force)
              throw new Exception('updating '.$model->getTables().' failure.');
          }
          else
          {
            $completes++;
          }
        }
      }
    });

    return $completes;
  }

}