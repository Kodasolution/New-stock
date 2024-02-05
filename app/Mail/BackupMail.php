<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Contracts\Queue\ShouldQueue;

class BackupMail extends Mailable
{
    use Queueable, SerializesModels;
    public $backFile;
    public $date;

    /**
     * Create a new message instance.
     */
    public function __construct($backFile, $date = null)
    {
        $this->backFile = storage_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Paradise' . DIRECTORY_SEPARATOR . $backFile;
        // $this->backFile = $backFile;
        $this->date = $date;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('admin@paradise.bi', "Paradise Restaurant"),
            subject: 'Backup reussi Paradise le ' . Carbon::now()->format('Y-d-m-h'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content()
    {
        return new Content(
            html: 'emails.backup',
        );
        // return $this
        //     ->from('admin@paradise.bi')
        //     ->markdown('emails.backup')
        //     ->attach($this->backFile);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->backFile),
        ];
    }
}
