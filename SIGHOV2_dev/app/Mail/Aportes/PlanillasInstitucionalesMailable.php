<?php

namespace App\Mail\Aportes;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class PlanillasInstitucionalesMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $datos_institucion;
    public $asunto;
    public $id_mes;
    public $gestion;
    public $id_tipo_aporte;

    /**
     * Create a new message instance.
     */
    public function __construct($asunto, $correo_ins, $id_mes, $gestion, $id_tipo_aporte)
    {
        $this->datos_institucion = $correo_ins;
        $this->asunto = $asunto;
        $this->id_mes = $id_mes;
        $this->gestion = $gestion;

        $this->id_tipo_aporte = match ($id_tipo_aporte) {
            '0' => 'PATRONAL',
            '1' => 'REINTEGRO',
            '2' => 'BENEFICIO SOCIAL',
            '3' => 'PLANILLA ADICIONAL',
            default => 'DESCONOCIDO',
        };
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
            view: 'Aportes.AportesInstitucionales.Mails.MailPlanillaCargada',
            with: [
                'datos_institucion' => $this->datos_institucion,
                'asunto' => $this->asunto,
                'id_mes' => $this->id_mes,
                'gestion' => $this->gestion,
                'id_tipo_aporte' => $this->id_tipo_aporte,
            ]
        );
    }

    /**
     * Build the message.
     *
     * @return $this
     */

}
