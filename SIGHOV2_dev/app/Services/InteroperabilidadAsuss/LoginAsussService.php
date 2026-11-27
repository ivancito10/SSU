<?php

namespace App\Services\InteroperabilidadAsuss;

use App\Models\Administracion\AccesoAsussToken;
use App\Models\Administracion\CredencialAsuss;
use App\Models\Administracion\LinkAsuss;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class LoginAsussService
{
    protected string $linkAuthAsuss;

    protected string $userAsuss;

    protected string $passwordAsuss;

    public function __construct()
    {
        $this->linkAuthAsuss = LinkAsuss::where('descripcion', 'LOGIN')
            ->where('estado', true)
            ->first()
            ->link;

        $credenciales = CredencialAsuss::where('estado', true)->first();

        $this->userAsuss = $credenciales->usuario_asuss;
        $this->passwordAsuss = $credenciales->password_asuss;

        //dd($this->linkAuthAsuss, $this->userAsuss, $this->passwordAsuss);
        //throw new \Exception('Not implemented');
    }

    public function generarToken()
    {
        /*$tokenRegistrado = AccesoAsussToken::where('created_at', Carbon::today())
            ->latest()
            ->first();

        if ($tokenRegistrado) {
            return $tokenRegistrado->token;
        }*/

        $respuesta = Http::withoutVerifying()
            ->post($this->linkAuthAsuss, [
                'username' => $this->userAsuss,
                'password' => $this->passwordAsuss,
            ]);

        //dd($respuesta->json());

        if ($respuesta->successful()) {
            $success = $respuesta->json('success');
            $token = $respuesta->json('token');

            if ($success === true && !empty($token)) {

                $ultimoRegistro = AccesoAsussToken::latest()->first();
                $ultimoRegistro->estado = false;
                $ultimoRegistro->save();

                AccesoAsussToken::create([
                    "username" => $this->userAsuss,
                    "password" => $this->passwordAsuss,
                    'token' => $token,
                    'estado' => true,
                    'ip' => request()->ip(),
                    'nombre_equipo' => gethostname(),
                    'id_user_created' => auth()->user()->id ?? null,
                ]);
            }
        }
    }
}
