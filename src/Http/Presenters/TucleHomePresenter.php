<?php namespace Eyewill\TucleCore\Http\Presenters;

class TucleHomePresenter extends Presenter
{
  protected $viewBase = 'tucle::home.';

  public function title()
  {
    return config('tucle.brand', 'TUCLE5').' 管理画面';
  }

}
