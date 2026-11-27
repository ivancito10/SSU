<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Administracion\AccesoAsussToken;
use Illuminate\Support\Facades\Auth;
use App\Models\Administracion\LinkServicio;
use App\Models\Administracion\CredencialAsuss;
use Illuminate\Http\Request;

class ConexionAsuss
{
    public function login()
    {
        $credencial = CredencialAsuss::where('estado', true)->orderBy('id', 'desc')->first();

        $credenciales = [
            "username" => $credencial->usuario_asuss,
            "password" => $credencial->password_asuss
        ];

        $link_servicio = LinkServicio::where('nombre_servicio', 'LOGIN ASUSS')->first();

        $asuss_login_link = $link_servicio->link_servicio;

        $response = Http::withoutVerifying()
            ->post($asuss_login_link, $credenciales);

        if ($response->successful()) {
            $datos = $response->json();

            // Asegúrate de que el usuario está autenticado
            if (!Auth::check()) {
                return response()->json(['message' => 'Usuario no autenticado', 'estado' => 0], 401);
            }

            $userId = Auth::user()->id;

            try {
                // Crear el registro del token en la base de datos
                $user = AccesoAsussToken::create([
                    "username" => $credencial->usuario_asuss,
                    "password" => $credencial->password_asuss,
                    'token' => $datos['token'],
                    'estado' => 1,
                    'ip' => request()->ip(),
                    'nombre_equipo' => gethostname(),
                    'id_user_created' => $userId,
                ]);

                return response()->json(['message' => 'Login Exitoso', 'respuesta' => $user, 'estado' => 1], 200);
            } catch (\Exception $e) {
                // Registrar el error y devolver una respuesta
                \Log::error('Error al crear AccesoAsussToken: ' . $e->getMessage());
                return response()->json(['message' => 'Error al crear el registro del token', 'estado' => 0], 500);
            }
        } else {
            // Manejar fallo en la solicitud HTTP
            return response()->json(['message' => 'Login Fallido', 'estado' => 0], $response->status());
        }
    }



    // NO SE USA
    public function login2()
    {
        $credenciales = [
            "username" => "ssu-lp",
            "password" => "ssu-lp.2024**"
        ];
        $response = Http::withoutVerifying()
            ->post('https://test.asuss.gob.bo/asuss-asegurado-api/api/v1/login', $credenciales);
        // $status = $response->status();
        // dd($status);
        if ($response->successful()) {
            $datos = $response->json();
            // $conection = response()->json([
            //     'message' => 'Login Exitoso',
            //     'respuesta' => $datos,
            //     'estado' => 1
            // ], 200);
            $user = AccesoAsussToken::create([

                "username" => "ssu-lp",
                "password" => "ssu-lp.2024**",
                'token' => $datos['token'],
                'estado' => 1,
                // 'id_user_created' => auth()->user()->id,
                'id_user_created' => 12,
            ]);
            return $user;
        } else {
            // Manejar error en caso de que la solicitud falle
            return response()->json(['message' => 'Login Fallido', 'estado' => 0], $response->status());
        }
    }
}
