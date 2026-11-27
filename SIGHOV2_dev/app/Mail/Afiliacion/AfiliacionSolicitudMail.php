<?php

namespace App\Mail\Afiliacion;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AfiliacionSolicitudMail extends Mailable
{
    use Queueable, SerializesModels;
    public $asunto;
    public $datosEstudiante;
    public $usuario;
    public $contraseña;

    /**
     * Create a new message instance.
     */
    public function __construct($asunto, $datosEstudiante, $usuario, $contraseña)
    {
        $this->asunto = $asunto;
        $this->datosEstudiante = $datosEstudiante;
        $this->usuario = $usuario;
        $this->contraseña = $contraseña;
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
            view: 'Afiliacion.Ssue.Mails.AfiliacionSolicitud',
            with: [
                'datosEstudiante' => $this->datosEstudiante,
                'usuario' => $this->usuario,
                'contraseña'=> $this->contraseña,
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
