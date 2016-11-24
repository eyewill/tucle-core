<?php namespace Eyewill\TucleCore\Console\Commands;

use Exception;
use Eyewill\TucleCore\Initializer;
use Illuminate\Console\Command;

class TucleInit extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'tucle:init {--force} {--only=} {--list}';

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
    $list = $this->option('list');

    try {
      $factory = new Initializer($force, $only);
      if ($list)
      {
        $this->info('all tasks.');
        $this->info('----------');
        $this->info(implode(PHP_EOL, $factory->getRegisteredTasks()));
      }
      else
      {
        foreach ($factory->generator() as $message)
        {
          $this->info($message);
        }
        $this->info('Tucle apps initialized.');
      }
    } catch (Exception $e) {

      $this->error($e->getFile().':'.$e->getLine().' '.$e->getMessage());
      exit(-1);
    }
  }
}
