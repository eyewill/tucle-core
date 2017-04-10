<?php namespace Eyewill\TucleCore\Contracts\Eloquent;

interface ExpirableInterface
{
  public function candidates();
  public function published();
  public function effective();
  public function publish();
  public function terminate();

}
