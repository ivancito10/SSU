<?php

namespace App\Livewire\Publicos\Certificados;

use App\Models\Administracion\Persona;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class CertificadoNoAfiliacion extends Component
{

    public $apellido_paterno;
    public $apellido_materno;
    public $nombres;
    public $ci;
    public $expedido;
    public $fecha_nacimiento;
    public $recaptchaToken;
    public $idUsuario;
    protected $listeners = ['setRecaptcha' => 'setToken'];

    public function setToken($token)
    {
        $this->recaptchaToken = $token;
    }


    public function render()
    {
        return view('livewire.publicos.certificados.certificado-no-afiliacion');
    }

    public function verificar()
    {

        $this->validate([
            'nombres'           => 'required|string|min:3',
            'ci'                => 'required|string|min:4',
            'fecha_nacimiento'  => 'required|date',
        ]);

        // Verificación del reCAPTCHA
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $this->recaptchaToken,
        ]);
        $result = $response->json();


        if ($result['success'] === true && $result['score'] > 0.8) {
            // dd($result, $result['success'], $result['score']);
            //codigo para verificar persona
            $dato_persona = Persona::where('ci', $this->ci)
                ->where('fecha_nacimiento', $this->fecha_nacimiento)
                ->first();
            // dd($dato_persona);
            if (!$dato_persona) {
                // NO existe en la BD se puede sacar
                session()->put('certificado_no_afiliacion', [
                    'nombres' => $this->nombres,
                    'ci' => $this->ci,
                    'fecha_nacimiento' => $this->fecha_nacimiento,
                    'apellido_paterno' => $this->apellido_paterno,
                    'apellido_materno' => $this->apellido_materno,
                    'created_at' => now(),
                ]);

                $this->js('window.open("/CertificadoNoAfiliacion/pdf", "_blank")');
                $this->dispatch('verificacionExitosa');
                $this->resetearCampos();
            } elseif ($dato_persona->id_tipo_asegurado === 0) {
                // Existe pero NO está asegurada se puede sacar
                session()->put('certificado_no_afiliacion', [
                    'nombres' => mb_strtoupper($dato_persona->nombres, 'UTF-8'),
                    'ci' => mb_strtoupper($dato_persona->ci, 'UTF-8'),
                    'fecha_nacimiento' => $dato_persona->fecha_nacimiento,
                    'apellido_paterno' => mb_strtoupper($dato_persona->p_apellido, 'UTF-8'),
                    'apellido_materno' => mb_strtoupper($dato_persona->s_apellido, 'UTF-8'),
                    'created_at' => now(),
                ]);

                $this->js('window.open("/CertificadoNoAfiliacion/pdf", "_blank")');
                $this->dispatch('verificacionExitosa');
                $this->resetearCampos();
            } else {
                // Existe y SÍ está asegurada NO se puede sacar
                $this->resetearCampos();
                $this->dispatch('personaAsegurada');
            }
            //dd($verificacion_persona);
            //
            //dd(strtoupper($this->matricula),$this->ci,$this->fecha_nacimiento,$result,$this->recaptchaToken);
            session()->flash('success', 'Verificación exitosa. Bienvenido!');

            $this->dispatch('resetRecaptcha');
            return;
        } else {
            $this->addError('recaptcha', 'Verificación fallida. Por favor, intente nuevamente.');

            $this->dispatch('reload-page');
        }
    }
    public function resetearCampos()
    {
        $this->apellido_paterno = '';
        $this->apellido_materno = '';
        $this->nombres = '';
        $this->ci = '';
        $this->fecha_nacimiento = '';
    }
}
