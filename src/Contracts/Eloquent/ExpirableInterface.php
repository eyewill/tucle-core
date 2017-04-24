<?php namespace Eyewill\TucleCore\Contracts\Eloquent;

interface ExpirableInterface
{
  /**
   * 公開前
   * @return mixed
   */
  public function candidates();

  /**
   * 公開中
   * @return mixed
   */
  public function published();

  /**
   * 公開前＋公開中
   * @return mixed
   */
  public function effective();

  /**
   * 公開処理
   * @return mixed
   */
  public function publish();

  /**
   * 公開終了処理
   * @return mixed
   */
  public function terminate();

}
