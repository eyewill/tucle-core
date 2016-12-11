<?php namespace Eyewill\TucleCore\Console\Commands;

use Exception;
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
      $factory = app()->make('Eyewill\TucleCore\Factories\InitializerFactory');
      $initializer = $factory->make($force, $only);

      $tasks = $initializer->getAllTasks();
      if ($list)
      {
        $this->showTasks($tasks);
        return;
      }

      if (!is_null($only))
      {
        foreach (explode(',', $only) as $task)
        {
          if (!in_array($task, $tasks))
          {
            $this->error('task "'.$task.'" is not defined.');
            $this->showTasks($tasks);
            return;
          }
        }
      }

      foreach ($initializer->generator() as $message)
      {
        $this->info($message);
      }
      $this->info('Tucle apps initialized.');

    } catch (Exception $e) {

      $this->error($e->getFile().':'.$e->getLine().' '.$e->getMessage());
      exit(-1);
    }
  }

  protected function showTasks($tasks)
  {
    $this->info('available task is only ['.implode(', ', $tasks).'].');
  }
}
