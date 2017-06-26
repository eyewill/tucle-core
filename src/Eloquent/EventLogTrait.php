<?php namespace Eyewill\TucleCore\Eloquent;

use Illuminate\Database\Eloquent\Model;

trait EventLogTrait
{
  public $logging = true;

  public static function bootEventLogTrait()
  {
    static::created(function (Model $model) {
      static::logging($model, $model->getTable().'.create');
    });

    static::updated(function (Model $model) {
      static::logging($model, $model->getTable().'.update', '', $model->getDirty());
    });

    static::deleted(function (Model $model) {
      static::logging($model, $model->getTable().'.delete');
    });

    if (method_exists(get_called_class(), 'restored'))
    {
      static::restored(function (Model $model) {
          static::logging($model, $model->getTable().'.restore');
      });
    }
  }

  protected static function logging(Model $model, $event, $description = '', $params = null)
  {
    if (!$model->logging)
    {
      return;
    }

    if (!auth()->check())
    {
      return;
    }

    $description = $description ?: $model->title;

    eventlog(auth()->user(), $event, $description, $params);
  }
}