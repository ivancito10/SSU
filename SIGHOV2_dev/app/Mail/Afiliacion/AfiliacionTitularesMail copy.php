<?php

namespace App\Mail\Afiliacion;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class AfiliacionTitularesMail extends Mailable
{
    use Queueable, SerializesModels;
    public $datos_estudiante;
    public $asunto;
    public $imageBase64;
    /**
     * Create a new message instance.
     */
    public function __construct($asunto,$datos_estudiante,$imageBase64)
    {
        //
        $this->datos_estudiante=$datos_estudiante;
        $this->asunto=$asunto;
        $this->imageBase64=$imageBase64;
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
            view: 'Afiliacion.Ssue.Mails.AfiliacionEstudiantes',
            with: [
                'datos_titular' => $this->datos_estudiante,'imageBase64'=>$this->imageBase64
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function build()
    {
        // Obtener el contenido del archivo desde el disco FTP
        
        $fileContent = Storage::disk('ftp')->get('Afiliacion/SSUE/CARNET_VIRTUAL/carnet_prueba.pdf');
        dd($fileContent,$this->id_user);
        return $this->view('Afiliacion.Ssue.Mails.AfiliacionEstudiantes')
                    ->subject($this->asunto)
                    ->with([
                        'datos_titular' => $this->datos_estudiante,
                        'imageBase64'=>$this->imageBase64
                    ])
                    ->attachData($fileContent, 'credencial_estudiante_SIGHO - .pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }

}
