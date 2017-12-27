<?php namespace Eyewill\TucleCore;

use Illuminate\Support\Collection;

/**
 * Class ModuleManager
 * @package Eyewill\TucleCore
 *
 * @method Module[] all
 */
class ModuleManager extends Collection
{
  public function __construct($items = [])
  {
    foreach ($items as $i => $module)
    {
      $items[$i] = new Module($module);
    }

    parent::__construct($items);
  }

  public function find($name)
  {
    return $this->first(function($module, $index) use ($name) {
      return ($module->name == $name);
    });
  }
}