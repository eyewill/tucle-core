<?php namespace Eyewill\TucleCore\Http\Presenters;

use Gate;
use Schema;

class TucleHomePresenter extends Presenter
{
  protected $viewBase = 'tucle::home.';

  protected $entries;

  public function title()
  {
    return config('tucle.brand', 'TUCLE5').' 管理画面';
  }

  public function entries()
  {
    if (is_null($this->entries))
    {
      $entries = [];
      foreach (module()->all() as $module)
      {
        if (Gate::allows('show-'.$module->name(), $module->model))
        {
          $updatedAt = '-';
          if (Schema::hasColumn(app($module->model)->getTable(), 'updated_at'))
          {
            $newest = app($module->model)->orderBy('updated_at', 'desc')->first();
            $updatedAt = $newest && $newest->updated_at ? $newest->updated_at->format('Y/m/d H:i') : '-';
          }
          $entries[] = [
            'label' => $module->label(),
            'url' => $module->url(),
            'count' => app($module->model)->count(),
            'updated_at' => $updatedAt,
          ];
        }
      }

      $this->entries = $entries;
    }

    return $this->entries;
  }
}
