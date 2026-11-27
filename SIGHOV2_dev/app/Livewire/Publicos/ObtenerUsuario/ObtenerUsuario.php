<?php

namespace App\Livewire\Publicos\ObtenerUsuario;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use App\Models\Administracion\Persona;
use App\Models\Administracion\SeguimientoObtencionUsuario;
use App\Models\User;

class ObtenerUsuario extends Component
{
    public $matricula;
    public $ci;
    public $nombres;
    public $fecha_nacimiento;
    public $recaptchaToken;
    public $ci_complemento;
    public $idUsuario;
    protected $listeners = ['setRecaptcha' => 'setToken'];

    public function setToken($token)
    {
        $this->recaptchaToken = $token;
    }

    public function verificar()
    {
        // Validación básica de los campos del formulario
        $this->validate([
            'matricula' => 'required|alpha_num|size:9',
            'ci' => 'required|digits_between:3,15|numeric',
            'ci_complemento' => 'nullable|string|max:2',
            'fecha_nacimiento' => 'required|date|before:today',
        ]);

        // Verificación del reCAPTCHA
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $this->recaptchaToken,
        ]);

        $result = $response->json();

        //    $error=$result['score'].$result['error-codes'] ?? null.request()->ip();


        if ($result['success'] === true && $result['score'] > 0.8) {

            //codigo para verificar usuario 
            $this->matricula = strtoupper($this->matricula);
            $seguimiento = SeguimientoObtencionUsuario::create([
                'matricula_seguro' => $this->matricula,
                'numero_ci' => $this->ci,
                'complemento_ci' => $this->ci_complemento,
                'fecha_nacimiento' => $this->fecha_nacimiento,
                'ip_address' => request()->ip(),
                'device_name' => request()->server('HTTP_USER_AGENT'),
                'captcha_token' => $this->recaptchaToken,
                'recuperado' => false
            ]);
            $verificacion_persona = Persona::where('ci', 'like', $this->ci)->where('matricula_seguro', 'like', $this->matricula)->where('fecha_nacimiento', '=', $this->fecha_nacimiento)->first();
            //dd($verificacion_persona,$this->matricula,$this->ci);
            if ($verificacion_persona) {

                $datos_usuario = User::where('id_persona', $verificacion_persona->id)->first();
                //dd($datos_usuario,$verificacion_persona->id_persona);
                $this->idUsuario = $datos_usuario->id;
                if ($datos_usuario->last_login_at == null && $datos_usuario->primer_inicio_sesion == null) {
                    $this->idUsuario = $datos_usuario->id;
                    $seguimiento->update([
                        'recuperado' => true,
                    ]);
                    // dd('ya casi bro',$this->idUsuario);
                    $this->js('window.open("/ObtenerUsuario/pdf/' . $this->idUsuario . '", "_blank")');
                    $this->dispatch('verificacionExitosa');
                } else {
                    $this->resetearCampos();
                    $this->dispatch('UsuarioYaGenerado');

                    //dd("no se entrega");
                }
            } else {
                $this->resetearCampos();
                $this->dispatch('personaNoExistente');
            }
            //dd($verificacion_persona);
            //
            //dd(strtoupper($this->matricula),$this->ci,$this->fecha_nacimiento,$result,$this->recaptchaToken);
            session()->flash('success', 'Verificación exitosa. Bienvenido!');
            $this->resetearCampos();
            $this->dispatch('resetRecaptcha');
            return;
        } else {
            $this->addError('recaptcha', 'Verificación fallida. Por favor, intente nuevamente.');

            $this->dispatch('reload-page');
        }

        // Si pasa la verificación


        // Aquí puedes agregar la lógica adicional después de la verificación
    }
    public function resetearCampos()
    {
        $this->reset('matricula', 'ci', 'nombres', 'fecha_nacimiento', 'recaptchaToken', 'ci_complemento');
    }

    public function render()
    {
        return view('livewire.publicos.obtener-usuario.obtener-usuario');
    }
}
