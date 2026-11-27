<?php

namespace App\Mail\Afiliacion;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AfiliacionBeneficiariosMail extends Mailable
{
    use Queueable, SerializesModels;
    public $datos_beneficiario;
    public $asunto;
    public $imageBase64;
    public $usuario,$contraseña;
    /**
     * Create a new message instance.
     */
    public function __construct($asunto,$datos_beneficiario,$imageBase64,$usuario,$contraseña)
    {
        //
        $this->datos_beneficiario=$datos_beneficiario;
        $this->asunto=$asunto;
        $this->imageBase64=$imageBase64;
        $this->usuario=$usuario;
        $this->contraseña=$contraseña;
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
            view: 'Afiliacion.Beneficiarios.Mails.AfiliacionBeneficiarios',
            with: [
                'datos_beneficiario' => $this->datos_beneficiario,'imageBase64'=>$this->imageBase64,'usuario'=>$this->usuario,'contraseña'=>$this->contraseña
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
