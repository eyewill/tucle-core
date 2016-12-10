<?php namespace Eyewill\TucleCore\Contracts\Eloquent;

interface ExpirableInterface
{
  public function candidates();
  public function published();
  public function effective();
}
