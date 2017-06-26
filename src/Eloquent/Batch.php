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
   * @param $name
   * @param array $entries
   * @param bool $force
   * @return int
   */
  public static function batch($name, $entries = [], $force = false)
  {
    $completes = 0;
    $results = [];

    try {

      app()->make('db')->transaction(function () use ($name, $entries, &$completes, &$results, $force) {

        /** @var Model $model */
        foreach ($entries as $model)
        {
          $model->logging = false;

          if (!$model->$name())
          {
            if (!$force)
            {
              throw new Exception('batch failure on '.$model->getTable().' id '.$model->id.'.');
            }
            continue;
          }

          if (!array_key_exists($model->getTable(), $results))
          {
            $results[$model->getTable()] = [];
          }
          $results[$model->getTable()][] = $model->id;
          $completes++;
        }

      });

    } catch (Exception $e) {

    }

    eventlog(auth()->user(), app()->make(static::class)->getTable().'.batch.'.$name, '', $results);

    return $completes;
  }
}