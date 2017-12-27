<?php namespace Eyewill\TucleCore\Database\Migrations;

use Illuminate\Database\Migrations\MigrationCreator as IlluminateMigrationCreator;

class MigrationCreator extends IlluminateMigrationCreator
{
  protected function getStub($table, $create)
  {
    $prefix = $create ? 'create_' : 'update_';
    return $this->files->get($this->getStubPath().'/'.$prefix.$table.'.stub');
  }

  public function getStubPath()
  {
    return __DIR__.'/../../../files/database/migrations';
  }
}