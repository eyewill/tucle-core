<?php namespace Eyewill\TucleCore\Database\Migrations;

use Illuminate\Database\Migrations\MigrationCreator as IlluminateMigrationCreator;

class MigrationCreator extends IlluminateMigrationCreator
{
  protected function getStub($table, $create)
  {
    return $this->files->get($this->getStubPath().'/event_logs.stub');
  }

  public function getStubPath()
  {
    return __DIR__.'/stubs';
  }
}