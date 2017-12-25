<?php namespace Eyewill\TucleCore;

use File;
use Symfony\Component\Process\Process;

class ComposerManager
{
  protected $path;

  protected $updates = false;

  public function __construct()
  {
    $this->path = base_path('composer.json');
  }


  public function add($name, $version, $dev = false)
  {
    if (!File::exists($this->path))
    {
      return 'composer.json not exists.';
    }

    $composerJson = json_decode(File::get($this->path), true);
    $prefix = $dev ? 'require-dev.' : 'require.';
    if (array_has($composerJson, $prefix.$name))
    {
      return $name.':'.$version.' already exists.';
    }

    array_set($composerJson, $prefix.$name, $version);
    $this->updates = true;
    File::put($this->path, json_encode($composerJson, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
    return 'add '.$name.':'.$version.' to composer.json';
  }

  public function addAutoload($name, $value, $dev = false)
  {
    if (!File::exists($this->path))
    {
      return 'composer.json not exists.';
    }

    $composerJson = json_decode(File::get($this->path), true);
    $baseKey = $dev ? 'autoload-dev' : 'autoload';
    $values = [];
    if (!is_array($value))
    {
      $values[] = $value;
    }
    else
    {
      $values = $value;
    }

    if (!array_key_exists($baseKey, $composerJson))
    {
      $composerJson[$baseKey] = [];
    }
    if (!array_key_exists($name, $composerJson[$baseKey]))
    {
      $composerJson[$baseKey][$name] = [];
    }
    if (array_values($values) === $values) // 配列(連想配列ではない)
    {
      foreach ($values as $value)
      {
        $composerJson[$baseKey][$name][] = $value;
      }
    }
    else
    {
      foreach ($values as $key => $value)
      {
        $composerJson[$baseKey][$name][$key] = $value;
      }
    }

    $this->updates = true;
    File::put($this->path, json_encode($composerJson, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
    return 'add values to '.$baseKey.':'.$name.' in composer.json';
  }


  public function scripts($command, $index = 1, $event = 'post-update-cmd')
  {
    if (!File::exists($this->path))
    {
      return 'composer.json not exists.';
    }

    $composerJson = json_decode(File::get($this->path), true);
    $commandList = array_get($composerJson, 'scripts.'.$event);
    if (in_array($command, $commandList))
    {
      return $command. ' already exists.';
    }

    array_splice($commandList, $index, 0, $command);
    array_set($composerJson, 'scripts.'.$event, $commandList);
    File::put($this->path, json_encode($composerJson, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));

    return 'add '.$command.' to '.$event;
  }

  public function update()
  {
    if (!$this->updates)
    {
      return 'composer.json not updated.';
    }

    $process = new Process('composer update');
    $process->setTimeout(0);
    $process->run();
    $this->updates = false;
    return $process->getOutput();
  }

  public function dumpAutoload()
  {
    $process = new Process('composer dumpautoload');
    $process->setTimeout(0);
    $process->run();
    $this->updates = false;
    return $process->getOutput();
  }
}