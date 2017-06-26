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

    static::$logging = false;

    try {

      app()->make('db')->transaction(function () use ($name, $entries, &$completes, $force) {

        /** @var Model $model */
        foreach ($entries as $model)
        {
          if (!$model->$name())
          {
            if (!$force)
            {
              throw new Exception('batch failure on '.$model->getTable().' id '.$model->id.'.');
            }
            continue;
          }

          $completes++;
        }

      });

    } catch (Exception $e) {

    }

    static::$logging = true;

    eventlog(auth()->user(), app()->make(static::class)->getTable().'.batch.'.$name);

    return $completes;
  }
}