<?php

namespace App\Mail\Aportes;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
class PlanillasIntitucionalesLiquidadaMailable extends Mailable
{
    use Queueable, SerializesModels;
    public $datos_institucion;
    public $asunto;
    public $id_mes;
    public $gestion;
    public $comprobante;
    public $compId;
    /**
     * Create a new message instance.
     */
    public function __construct($asunto,$correo_ins,$id_mes,$gestion,$comprobante,$compId)
    {
        //
        $this->datos_institucion=$correo_ins;
        $this->asunto=$asunto;
        $this->id_mes=$id_mes;
        $this->gestion=$gestion;
        $this->comprobante=$comprobante;
        $this->compId=$compId;

        
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
            view: 'Aportes.AportesInstitucionales.Mails.MailPlanillaLiquidada',
            with: [
                'datos_institucion' => $this->datos_institucion,
                'asunto'=>$this->asunto,
                'id_mes'=>$this->id_mes,
                'gestion'=>$this->gestion,
                'comprobante'=>$this->comprobante,
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
        
        $fileContent = Storage::disk('ftp')->get('Aportes/Comprobantes/'.$this->compId.'.pdf');
        // dd($fileContent,$this->compId);
        return $this->view('Aportes.AportesInstitucionales.Mails.MailPlanillaLiquidada')
                    ->subject($this->asunto)
                    ->with([
                        'datos_institucion' => $this->datos_institucion,
                        'asunto' => $this->asunto,
                        'id_mes' => $this->id_mes,
                        'gestion' => $this->gestion,
                        'comprobante' => $this->comprobante,
                    ])
                    ->attachData($fileContent, 'comprobante - '.$this->id_mes.' - '.$this->gestion.'.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }

    
}
