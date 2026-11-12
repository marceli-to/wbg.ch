<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * Sent to the admin who deleted a project, listing the project relations
 * that were removed together with it.
 */
class ProjectRelationsDeleted extends Mailable
{
    /**
     * @param  array<int, array{from: string, to: string}>  $data
     */
    public function __construct(public array $data) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('no-reply@wbg.ch', 'WBG AG - Visuelle Kommunikation'),
            subject: 'Gelöschte Projektrelationen',
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'web.emails.project');
    }
}
