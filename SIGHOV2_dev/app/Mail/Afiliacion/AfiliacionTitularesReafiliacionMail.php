<?php

namespace App\Mail\Afiliacion;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class AfiliacionTitularesReafiliacionMail extends Mailable
{
    use Queueable, SerializesModels;
    public $datos_titular;
    public $asunto;
    public $imageBase64;
    public $usuario,$contraseña,$id_user;
    /**
     * Create a new message instance.
     */
    public function __construct($asunto,$datos_titular,$imageBase64,$usuario,$contraseña,$id_user)
    {
        //
        $this->datos_titular=$datos_titular;
        $this->asunto=$asunto;
        $this->imageBase64=$imageBase64;
        $this->usuario=$usuario;
        $this->contraseña=$contraseña;
        $this->id_user=$id_user;
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
            view: 'Afiliacion.Titulares.Mails.AfiliacionTitularesReafiliacion',
            with: [
                'datos_titular' => $this->datos_titular,'imageBase64'=>$this->imageBase64,'usuario'=>$this->usuario,'contraseña'=>$this->contraseña
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
        
        $fileContent = Storage::disk('ftp')->get('Administracion/Usuarios/Credenciales/'.$this->id_user.'.pdf');
        // dd($fileContent,$this->id_user);
        return $this->view('Afiliacion.Titulares.Mails.AfiliacionTitularesReafiliacion')
                    ->subject($this->asunto)
                    ->with([
                        'datos_titular' => $this->datos_titular,
                        'imageBase64'=>$this->imageBase64,
                        'usuario'=>$this->usuario,
                        'contraseña'=>$this->contraseña
                    ])
                    ->attachData($fileContent, 'usuario_sistema_SIGHO - '.$this->id_user.' - '.$this->datos_titular->nombres.'.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}
