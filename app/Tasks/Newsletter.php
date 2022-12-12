<?php
namespace App\Tasks;
use App\Models\Subscriber;

class Newsletter
{
  public function __invoke()
  {
    $subscribers = Subscriber::unprocessed()->get();
    $subscribers = collect($subscribers)->splice(0,1);
    $env = app()->environment();

    foreach($subscribers->all() as $subscriber)
    {
      $recipient = ($env == 'production' || $env == 'staging') && $subscriber->email ? $subscriber->email : env('MAIL_TO');
      try
      {
        \Mail::to($recipient)->send(
          new \App\Mail\Newsletter()
        );
        $subscriber->processed = 1;
        $subscriber->save();
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