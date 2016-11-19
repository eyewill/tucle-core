<?php namespace Eyewill\TucleCore\Console\Commands;

use Exception;
use Eyewill\TucleCore\Initialize;
use Illuminate\Console\Command;

class TucleInit extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'tucle:init {--force} {--only=}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Init Tucle app';

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
      $factory = new Initialize($force, $only);
      foreach ($factory->generator() as $message)
        $this->info($message);
    } catch (Exception $e) {

      $this->error($e->getFile().':'.$e->getLine().' '.$e->getMessage());
      exit(-1);
    }

    $this->info('Tucle apps initialized.');
  }
}
