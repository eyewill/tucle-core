
use Eyewill\TucleCore\Http\Presenters\TucleHomePresenter;

/**
* Index
* route GET /
* name home
*/
Route::get('/', function (TucleHomePresenter $presenter) {

  $entries = [];
  foreach (module()->all() as $module)
  {
    if (Gate::allows('show-'.$module->name(), $module->model))
    {
      $newest = app($module->model)->orderBy('updated_at', 'desc')->first();
      $entries[] = [
        'label' => $module->label(),
        'url' => $module->url(),
        'count' => app($module->model)->count(),
        'updated_at' => $newest ? $newest->updated_at->format('Y/m/d H:i') : '-',
      ];
    }
  }

  return view('tucle::home.index', [
    'presenter' => $presenter,
    'entries' => $entries,
  ]);

})->middleware('auth')->name('home');