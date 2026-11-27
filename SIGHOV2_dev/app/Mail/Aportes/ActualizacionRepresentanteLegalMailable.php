<?php

namespace App\Mail\Aportes;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ActualizacionRepresentanteLegalMailable extends Mailable
{
    use Queueable, SerializesModels;
    public $institucion;
    public $asunto;
    public $edit_rep;

    /**
     * Create a new message instance.
     */
    public function __construct($asunto, $institucion, $edit_rep)
    {
        //
        $this->institucion=$institucion;
        $this->asunto=$asunto;
        $this->edit_rep=$edit_rep;
      

        
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->asunto,
            cc: ['yanaram@ssulapaz.org'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'Aportes.Institucion.Mails.MailRepresentanteLegal',
            with: [
                'institucion' => $this->institucion,
                'asunto'=>$this->asunto,
                'edit_rep'=>$this->edit_rep,
                
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
