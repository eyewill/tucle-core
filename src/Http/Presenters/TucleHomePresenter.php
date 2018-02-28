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
        $newest = null;
        if (Gate::allows('show-'.$module->name(), $module->model))
        {
          if (method_exists($module->presenter, 'getTotal'))
          {
            $count = $module->presenter->getTotal($module->model);
            if (Schema::hasColumn(app($module->model)->getTable(), 'updated_at'))
            {
              $builder = $module->presenter->getEntriesBuilder($module->model)->orderBy('updated_at', 'desc');
              $newest = $module->presenter->getEntries($builder)->first();
            }
          }
          else
          {
            $count = app($module->model)->count();
            if (Schema::hasColumn(app($module->model)->getTable(), 'updated_at'))
            {
              $newest = app($module->model)->orderBy('updated_at', 'desc')->first();
            }
          }
          $updatedAt = $newest && $newest->updated_at ? $newest->updated_at->format('Y/m/d H:i') : '-';
          $entries[] = [
            'label' => $module->label(),
            'url' => $module->url(),
            'count' => $count,
            'updated_at' => $updatedAt,
          ];
        }
      }

      $this->entries = $entries;
    }

    return $this->entries;
  }
}
