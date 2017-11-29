<?php namespace Eyewill\TucleCore\Contracts\Eloquent;

interface SortableInterface
{
  public function sortOrder($order = -1);
}