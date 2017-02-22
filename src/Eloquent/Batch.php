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

    try {

      app()->make('db')->transaction(function () use ($entries, &$completes, $force) {

        /** @var Model $model */

        foreach ($entries as $entry)
        {
          $type = array_get($entry, 'type');
          $id = array_get($entry, 'id');
          if (array_has($entry, 'model'))
          {
            $model = app()->make($entry['model'])->find($id);
          }
          else
          {
            $model = static::find($id);
          }
          if ($type == 'delete')
          {
            if (!$model->delete())
            {
              if (!$force)
                throw new Exception('deleting '.$model->getTable().' failure.');
            }
            else
            {
              $completes++;
            }
          }
          elseif ($type == 'put')
          {
            $attributes = array_get($entry, 'attributes');
            $model->fill($attributes);
            if (!$model->save())
            {
              if (!$force)
                throw new Exception('updating '.$model->getTable().' failure.');
            }
            else
            {
              $completes++;
            }
          }
        }
      });

    } catch (Exception $e) {


    }

    return $completes;
  }

}