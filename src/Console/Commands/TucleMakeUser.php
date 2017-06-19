<?php namespace Eyewill\TucleCore\Console\Commands;

use Exception;
use Illuminate\Console\Command;

class TucleMakeUser extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'tucle:makeuser {--force} {--only=}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Scaffold Tucle user module';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    $force = $this->option('force');
    $only = $this->option('only');

    try {
      $factory = $this->getLaravel()->make(\Eyewill\TucleCore\Factories\UserModuleFactory::class);
      $builder = $factory->make($force, $only);

      foreach ($builder->generator() as $message)
      {
        $this->info($message);
      }

      $this->info('User module created!');

    } catch (Exception $e) {

      $this->error($e->getFile().':'.$e->getLine().' '.$e->getMessage());
    }
  }

}