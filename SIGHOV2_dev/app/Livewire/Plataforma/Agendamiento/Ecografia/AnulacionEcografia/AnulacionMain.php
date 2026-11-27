<?php

namespace App\Livewire\Plataforma\Agendamiento\Ecografia\AnulacionEcografia;

use Livewire\Component;
use App\Models\Plataforma\Agenda;
use App\Models\Plataforma\Especialista;
use App\Models\Plataforma\AsignacionHorario;
use App\Models\Plataforma\AnulacionAgenda;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Especialidad;
use App\Models\Administracion\TipoAsegurado;
use App\Models\Vigencia\Convenio;
use App\Models\Vigencia\AutorizacionInterior;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Estudiante;
use App\Models\Plataforma\AgendaEcografia;
use App\Models\Plataforma\AnulacionAgendaEcografia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Livewire\Attributes\On;

class AnulacionMain extends Component
{
    public $idAgenda;
    public $agenda;
    public $user_created;

    public function render()
    {
        return view('livewire.Plataforma.agendamiento.ecografia.anulacion-ecografia.anulacion-main');
    }

    public function redirectoAnulacionRegister()
    {
        return redirect()->route('plataforma.agendamiento.ecografia.anulacion-ecografia.selection');
    }

    // verAnulacion
    #[On('verAnulacion')]
    public function verAnulacion($data)
    {
        //dd($data);
        $this->idAgenda = $data;
        if ($this->idAgenda) {
            $this->mostrarAnulacion($this->idAgenda);
        } else {
            $this->agenda = '';
        }
        $this->dispatch('modalVerAnulacion');
    }

    public $fecha_agenda, $hora_agenda, $confirmado, $clave_unica;
    public $fecha_anulacion, $motivo_anulacion, $anulacion_ficha, $anuladoPor, $fecha_creacion;
    public $nombres, $matricula, $carnetAsegurado, $tipoAsegurado, $fotoAsegurado;
    public $nombreEspecialista, $carnetEspecialista, $matriculaEspecialista;
    public $nombreEspecialidad, $permanente, $fotoEspecialista;
    public $nombre_equipo, $ip, $servicio_agenda, $nombre_equipo_anulado, $ip_anulado;

    public function mostrarAnulacion($idAgenda)
    {
        try {
            if ($idAgenda) {
                $agendaAnulada = AgendaEcografia::with('servicioPlataformaAgenda')->find($idAgenda);
                if ($agendaAnulada) {
                    $persona = Persona::find($agendaAnulada->id_persona);
                    if ($persona) {
                        $this->nombres = $persona->nombres . ' ' . $persona->p_apellido . ' ' . $persona->s_apellido;
                        $this->matricula = $persona->matricula_seguro;
                        $carnet = $persona->ci;
                        $complemento = $persona->complemento;
                        if ($complemento == null) {
                            $this->carnetAsegurado = $carnet;
                        } else {
                            $this->carnetAsegurado = $carnet . ' ' . $complemento;
                        }
                    }

                    if ($persona->id_tipo_asegurado == 1) {
                        $titular = Titular::where('id_persona', $persona->id)->first();
                        $this->fotoAsegurado = $this->obtenerImagenBase64($titular->foto_nombre);
                    } elseif ($persona->id_tipo_asegurado >= 2 && $persona->id_tipo_asegurado <= 7) {
                        $beneficiario = Beneficiario::where('id_persona', $persona->id)->first();
                        $this->fotoAsegurado = $this->obtenerImagenBase64($beneficiario->foto_nombre);
                    } elseif ($persona->id_tipo_asegurado == 8) {
                        //Titular Convenio
                        $convenio = Convenio::where('id_persona', $persona->id)->first();
                        $this->fotoAsegurado = $this->obtenerImagenBase64Convenio($convenio->foto_nombre);
                    } elseif ($persona->id_tipo_asegurado == 9 || $persona->id_tipo_asegurado == 10) {
                        //Tilular y beneficiario del interior
                        $titularInterior = AutorizacionInterior::where('id_persona_titular', $persona->id)
                            ->orWhere('id_persona_interior', $persona->id)
                            ->first();
                        $this->fotoAsegurado = $this->obtenerImagenBase64Autorizacion($titularInterior->foto_nombre);
                    } elseif ($persona->id_tipo_asegurado == 11) {
                        //Estudiante
                        $estudiante = Estudiante::where('id_persona', $persona->id)->first();
                        $this->fotoAsegurado = $this->obtenerImagenBase64Estudiante($estudiante->foto_nombre);
                    } else {
                        $this->fotoAsegurado = "";
                    }

                    $tipo = TipoAsegurado::find($persona->id_tipo_asegurado);
                    if ($tipo) {
                        $this->tipoAsegurado = $tipo->tipo_asegurado;
                    }

                    $asignacion = AsignacionHorario::where('id', $agendaAnulada->id_asignacion_horario)->first();
                    if ($asignacion) {
                        $especialista = Especialista::where('id', $asignacion->id_especialista)->first();
                        if ($especialista) {
                            // Obtener los nombres del especialista desde la tabla Persona
                            $personaEspecialista = Persona::where('id', $especialista->id_persona)->first();
                            if ($personaEspecialista) {
                                $this->nombreEspecialista = $personaEspecialista->nombres . ' ' . $personaEspecialista->p_apellido . ' ' . $personaEspecialista->s_apellido;
                                $carnet = $personaEspecialista->ci;
                                $complemento = $personaEspecialista->complemento;
                                if ($complemento == null) {
                                    $this->carnetEspecialista = $carnet;
                                } else {
                                    $this->carnetEspecialista = $carnet . ' ' . $complemento;
                                }
                                $this->matriculaEspecialista = $personaEspecialista->matricula_seguro;
                                $this->permanente = $personaEspecialista->permanente;
                                $this->fotoEspecialista = $this->obtenerImagenBase64Doctores($especialista->id);
                            }
                            // Obtener la especialidad del especialista
                            $especialidad = Especialidad::where('id', $especialista->id_especialidad)->first();
                            if ($especialidad) {
                                $this->nombreEspecialidad = $especialidad->especialidad;
                            }
                        }
                    }

                    $this->fecha_agenda = $agendaAnulada->fecha_agenda;
                    $this->hora_agenda = $agendaAnulada->hora_agenda;
                    //dd($agendaAnulada->id_user_updated);
                    $usuarioCreador = User::find($agendaAnulada->id_user_created);
                    $this->user_created = $usuarioCreador->name;
                    $this->fecha_creacion = $agendaAnulada->created_at;
                    $this->nombre_equipo = $agendaAnulada->nombre_equipo;
                    $this->ip =  $agendaAnulada->ip;
                    $this->servicio_agenda = $agendaAnulada->servicioPlataformaAgenda->nombre_servicio;
                    $usuario = User::find($agendaAnulada->id_user_updated);
                    if ($usuario) {
                        $this->anuladoPor = $usuario->name;
                    }

                    // $this->fecha_anulacion = Carbon::parse($agendaAnulada->updated_at)->locale('es')->translatedFormat('j \\d\\e F \\d\\e Y');
                    $this->fecha_anulacion = $agendaAnulada->fecha_anulacion;
                    $this->motivo_anulacion = $agendaAnulada->motivo_anulacion;
                    $detalle_anulacion = AnulacionAgendaEcografia::find($agendaAnulada->id);
                    $this->ip_anulado = $detalle_anulacion?->ip ?: 'SIN DATO';
                    $this->nombre_equipo_anulado = $detalle_anulacion?->nombre_equipo ?: 'SIN DATO';
                    //dd($detalle_anulacion);
                } else {
                    $this->fotoEspecialista = "";
                }
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al mostrar anulacion", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    private function obtenerImagenBase64Doctores($idFoto)
    {
        try {
            if ($idFoto) {
                try {
                    $path = 'Plataforma/Especialista/Fotos/' . $idFoto . '.jpg';
                    $imageData = Storage::disk('ftp')->get($path);
                    $foto_medico = base64_encode($imageData);
                    return $foto_medico;
                } catch (\Exception $e) {
                    Log::error('Error al acceder al servidor FTP: ' . $e->getMessage());
                    return $foto_medico = null;
                }
            } else {
                return null;
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al mostrar imagen Doctores", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
            return null;
        }
    }

    private function obtenerImagenBase64($idFoto)
    {
        try {
            if ($idFoto) {
                $path = 'Afiliacion/FOTOS/' . $idFoto;
                $imageData = Storage::disk('ftp')->get($path);
                //dd($imageData);
                $foto = base64_encode($imageData);
                return $foto;
            } else {
                return null;
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al obtener imagen base 64", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
            return null;
        }
    }

    private function obtenerImagenBase64Estudiante($idFoto)
    {
        try {
            if ($idFoto) {
                $path = 'Afiliacion/SSUE/FOTOS/' . $idFoto;
                $imageData = Storage::disk('ftp')->get($path);
                $foto = base64_encode($imageData);
                return $foto;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    private function obtenerImagenBase64Convenio($idFoto)
    {
        try {
            if ($idFoto) {
                $path = 'Vigencia/FOTOS/CONVENIO/' . $idFoto;

                $imageData = Storage::disk('ftp')->get($path);

                $foto = base64_encode($imageData);
                return $foto;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    private function obtenerImagenBase64Autorizacion($idFoto)
    {
        try {
            if ($idFoto) {
                $path = 'Vigencia/FOTOS/CONVENIO/' . $idFoto;
                $imageData = Storage::disk('ftp')->get($path);
                $foto = base64_encode($imageData);
                return $foto;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }
}
