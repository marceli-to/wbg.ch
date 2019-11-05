<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Newsletter extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(
                        [
                            'address' => 'newsletter@wbg.ch',
                            'name' => 'WBG'
                        ]
                    )
                    ->replyTo('bf@wbg.ch')
                    ->subject('In eigener Sache')
                    ->markdown('web.emails.newsletter');
    }
}
