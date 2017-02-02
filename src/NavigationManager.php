<?php namespace Eyewill\TucleCore;

use Illuminate\Support\Collection;

/**
 * Class ModuleManager
 * @package Eyewill\TucleCore
 *
 * @method Navigation[] all
 */
class NavigationManager extends Collection
{
  public function __construct($items = [])
  {
    foreach ($items as $i => $module)
    {
      $items[$i] = new Navigation($module);
    }

    parent::__construct($items);
  }
}