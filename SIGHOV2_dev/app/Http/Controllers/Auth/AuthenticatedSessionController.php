<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Administracion\UserSession;
use App\Models\Administracion\Comunicado;
use App\Models\Administracion\Imagen;
use App\Models\Administracion\LinkServicio;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {

        $hoy = Carbon::today();
        $comunicados = Comunicado::where('estado', true)
            ->where(function ($query) use ($hoy) {
                $query->where('permanente', true)
                    ->orWhere(function ($subquery) use ($hoy) {
                        $subquery->where('permanente', false)
                            ->whereDate('fecha_inicio', '<=', $hoy)
                            ->whereDate('fecha_fin', '>=', $hoy);
                    });
            })
            ->orderBy('id', 'desc')
            ->get();

            $imagenes = Imagen::where('estado', true)
                  ->pluck('nombre_imagen'); 
            $fotos = [];

            foreach ($imagenes as $img) {
                $rutaFTP = 'Administracion/Login/Imagenes/' . $img;

                if (Storage::disk('ftp')->exists($rutaFTP)) {
                    $contenido = Storage::disk('ftp')->get($rutaFTP);
                    $mime = 'image/' . pathinfo($img, PATHINFO_EXTENSION);
                    $fotos[] = 'data:' . $mime . ';base64,' . base64_encode($contenido);
                }
            }

            return view('auth.login', [
                'comunicados' => $comunicados,
                'fotos' => $fotos
            ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // Generar un nuevo token de sesión

        $sessionToken = Str::random(60);
        //tiempo
        //         dd(gethostbyaddr($_SERVER['REMOTE_ADDR']),
        // $_SERVER["REMOTE_ADDR"]);
        $updated = $request->user()->update(['session_token' => $sessionToken, 'last_login_at' => Carbon::now()]);
        $ipAddress = $_SERVER["REMOTE_ADDR"];
        $userAgent = $request->header('User-Agent');

        $deviceName = gethostbyaddr($_SERVER['REMOTE_ADDR']); // Obtener el nombre del equipo del usuario
        UserSession::create([
            'user_id' => $request->user()->id,
            'session_token' => $sessionToken, // O cualquier token de sesión que uses
            'ip_address' => $ipAddress,
            'device_name' => $deviceName,
            'user_agent' => $userAgent,
            'login_at' => now(),
        ]);
        // Almacenar el token en la sesión del usuario
        $request->session()->put('session_token', $sessionToken);

        $request->session()->regenerate();

        $this->emitir_socket($request->user()->id);
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function emitir_socket($id_user)
    {
        $servicio = LinkServicio::where('estado', true)
            ->where('nombre_servicio', 'SERVER_SOCKET_IO')
            ->first();
        if ($servicio) {
            $ruta = $servicio->link_servicio;
            $user = User::where('id', $id_user)->first();
            // dd($user);
            $id_socket = $user->socket_id;
            if ($id_socket) {
                Http::post($ruta . '/control_session', [
                    'id_socket' => $id_socket,
                ]);
            }
        }
    }
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // dd(session('session_token'));
        $user = $request->user();

        // Actualizar el campo 'logout_at' de la sesión actual
        UserSession::where('user_id', $user->id)
            ->where('session_token', session('session_token'))
            ->update(['logout_at' => now()]);

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
