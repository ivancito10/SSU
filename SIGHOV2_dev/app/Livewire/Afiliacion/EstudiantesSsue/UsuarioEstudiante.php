<?php

namespace App\Livewire\Afiliacion\EstudiantesSsue;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Administracion\Role;
use App\Models\Administracion\UserSession;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class UsuarioEstudiante extends Component
{
    public $idUsuario, $name, $email, $datos, $new_password, $fecha_creacion, $usuario_creador, $estado, $roles_asignados = [],
        $lista_roles = [], $nuevos_roles = [], $fecha_registro, $usuario;
    #[locked]
    public $datos_usuario_ver,
        $v_name, $v_email, $v_fecha_registro, $v_usuario_registrador, $v_ci, $v_complemento, $v_fecha_nacimiento, $v_usuario_personar, $v_roles_asignados = [], $fotografia, $v_edad, $sesiones, $sessiones_c;
    public function render()
    {
        return view('livewire.Afiliacion.estudiantes-ssue.usuario-estudiante');
    }

    public function redirectToForm()
    {
        return redirect()->route('Afiliacion.Estudiantes.Usuario.register');
    }

    #[On('verUsuario')]
    public function verUsuario($data)
    {

        $this->idUsuario = $data;
        $this->datos_usuario_ver = $this->datosUsuario($data);
        $this->v_name = $this->datos_usuario_ver->name;
        $this->v_email = $this->datos_usuario_ver->email;
        $this->v_fecha_registro = $this->datos_usuario_ver->created_at;
        $datos_usuario_registro = User::with('personaUser')->find($this->datos_usuario_ver->id_user_created);
        $this->v_usuario_registrador = $datos_usuario_registro->personaUser->p_apellido . ' ' . $datos_usuario_registro->personaUser->s_apellido . ' ' . $datos_usuario_registro->personaUser->nombres;
        $this->v_ci = $this->datos_usuario_ver->personaUser->ci;
        $this->v_complemento = $this->datos_usuario_ver->personaUser->complemento;
        $this->v_fecha_nacimiento = Carbon::parse($this->datos_usuario_ver->personaUser->fecha_nacimiento)->Format('d /m/ Y ');

        $this->v_edad = Carbon::parse($this->datos_usuario_ver->personaUser->fecha_nacimiento)->age;
        $this->v_usuario_personar = $this->datos_usuario_ver->personaUser->p_apellido . ' ' . $this->datos_usuario_ver->personaUser->s_apellido . ' ' . $this->datos_usuario_ver->personaUser->nombres;
        $this->fotografia = $this->obtenerImagenBase64($this->datos_usuario_ver->imagen);
        $this->v_roles_asignados = $this->datos_usuario_ver->getRoleNames();
        $this->sesiones = UserSession::where('user_id', $this->idUsuario)->orderby('id', 'desc')->first();
        $this->sessiones_c = UserSession::where('user_id', $this->idUsuario)->orderby('id', 'desc')->count();
        //   dd($sessiones_c);
        $this->dispatch('modalVerUsuarios');
    }

    public function datosUsuario($data)
    {
        $datos_usuario = User::with('personaUser')->find($data);
        return $datos_usuario;
    }

    private function obtenerImagenBase64($filename)
    {
        try {
            if ($filename) {
                $path = 'Administracion/Usuarios/Perfil/' . $filename;
                $imageData = Storage::disk('ftp')->get($path);

                $fotito = base64_encode($imageData);
                return $fotito;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    #[On('verCredencialesPdf')]
    public function verCredencialesPdf($data)
    {

        $this->idUsuario = $data;
        // dd('ya casi bro',$this->idUsuario);
        $this->js('window.open("/Administracion/Usuarios/pdfCredencialesUsuario/' . $this->idUsuario . '", "_blank")');
    }
}
