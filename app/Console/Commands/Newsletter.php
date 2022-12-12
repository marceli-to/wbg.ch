<?php
namespace App\Console\Commands;
use App\Models\Subscriber;
use Illuminate\Console\Command;

class Newsletter extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'newsletter:send';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Run the newsletter task';

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
    $subscribers = Subscriber::unprocessed()->get();
    $subscribers = collect($subscribers)->splice(0,1);
    $env = app()->environment();

    foreach($subscribers->all() as $subscriber)
    {
      // $recipient = ($env == 'production') && $subscriber->email ? $subscriber->email : env('MAIL_TO');
      $recipient = $subscriber->email;
      try
      {
        \Mail::to($recipient)->send(
          new \App\Mail\Newsletter()
        );
        $subscriber->processed = 1;
        $subscriber->save();

        $this->info('Mail sent to: ' . $recipient);

      }
      catch(\Throwable $e)
      {
        \Log::error($e);
        $subscriber->error = $e;
        $subscriber->processed = 1;
        $subscriber->save();
      }
    }
  }
}
