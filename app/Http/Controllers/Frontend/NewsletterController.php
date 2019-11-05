<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use App\Mail\Newsletter;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    protected $subscriber;

    public function __construct(Subscriber $subscriber)
    {
        $this->subscriber = $subscriber;
    }

    public function send()
    {
        $subscribers = $this->subscriber->get();
        foreach($subscribers as $subscriber)
        {
            Mail::to($subscriber->email)->send(new Newsletter());
        }
    }
}
