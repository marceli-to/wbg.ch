<?php
namespace App\Console\Commands;
use App\Models\Subscriber;
use Illuminate\Console\Command;

class SubscriberImport extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'subscriber:import';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Import subscribers';

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
   * @return int
   */
  public function handle()
  {


    foreach($subscribers as $subscriber)
    {
      Subscriber::create([
        'email' => $subscriber,
        'processed' => 0
      ]);
    }

  }
}
