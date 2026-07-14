<?php

namespace App\Mail;

use App\Modules\Applications\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class ApplicationApproved extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $application;

    /**
     * Create a new message instance.
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Application Approved: ' . ($this->application->event->title ?? 'Event'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.application.approved',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        // Generate QR code and attach as string
        $qrCode = QrCode::format('png')->size(300)->generate($this->application->barcode_token);
        
        $attachments[] = Attachment::fromData(fn () => $qrCode, 'Ticket-QR.png')
                ->withMime('image/png');

        if ($this->application->event && $this->application->event->agenda_pdf_path) {
            $attachments[] = Attachment::fromStorageDisk('public', $this->application->event->agenda_pdf_path)
                ->as('Agenda.pdf')
                ->withMime('application/pdf');
        }

        return $attachments;
    }
}
