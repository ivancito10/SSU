<?php

namespace App\Mail\Aportes;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
class LiquidacionAporteVoluntarioMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $comprobante;
    public $asunto;
    public $datos_persona;
    public $nombre_comprobante;

     public function __construct($asunto, $comprobante, $datos_persona,$nombre_comprobante)
     {
         //
         $this->comprobante=$comprobante;
         $this->asunto=$asunto;
         $this->datos_persona=$datos_persona;
         $this->nombre_comprobante=$nombre_comprobante;
       
 
         
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
            view: 'Aportes.AportesVoluntarios.Mails.MailLiquidacionAportes',
            with: [
                'comprobante' => $this->comprobante,
                'asunto'=>$this->asunto,
                'datos_persona'=>$this->datos_persona,
                
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
        
        $fileContent = Storage::disk('ftp')->get('Aportes/Comprobantes/'.$this->nombre_comprobante.'.pdf');
        // dd($fileContent,$this->compId);
        return $this->view('Aportes.AportesInstitucionales.Mails.MailPlanillaLiquidada')
                    ->subject($this->asunto)
                    ->with([
                        'comprobante' => $this->comprobante,
                        'asunto'=>$this->asunto,
                        'datos_persona'=>$this->datos_persona,
                    ])
                    ->attachData($fileContent, 'comprobante - '.$this->comprobante->id_mes.' - '.$this->comprobante->gestion.'.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}
