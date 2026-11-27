<?php

namespace App\Livewire\Afiliacion\Afiliados;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Services\GrupoFamiliarPersona;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\TipoAsegurado;
use App\Models\Administracion\EstadoCivil;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\BeneficiarioInstitucion;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\Estudiante;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Afiliacion\CodigoInstitucionSsu;
use App\Models\Vigencia\HistorialAsegurado;
use App\Models\Aportes\Institucion;
use App\Models\Aportes\ContratoVoluntario;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

use function Pest\Laravel\withCookie;

class AfiliadosMain extends Component
{
    public $idAfiliado, $modal;
    public $datosFamiliaAfiliado;
    public $afiliado;
    public $foto;
    public $estado_vigencia;
    public $tipo_asegurado;
    public $datosCombinados = [];
    public $datosTitulares = [];
    public $datosBeneficiarios = [];
    public $datosBeneficiarioInstitucion = [];
    public $datosEstudiantes = [];
    public $count = 0;
    public $count_grupo;
    public $idPersona, $nombre_completo, $ci, $complemento, $matricula, $fecha_nacimiento, $sexo,
        $id_titular, $correo, $edad,
        $sigla, $estado_civil, $tipo_asegurado_m;
    public $historialAseguradoData;
    public $historialAsegurado, $fue_estudiante, $fue_titular, $fue_beneficiario;
    public $varEstudiante, $varBeneficiario, $varTitular;

    public function render()
    {
        return view('livewire.Afiliacion.afiliados.afiliados-main');
    }


    // Aqui mostramos los datos del Afiliado
    public function mostrarAfiliado($data)
    {
        $this->idPersona = $data;
        $persona = Persona::find($data);
        $this->nombre_completo = $persona->nombres . ' ' . $persona->p_apellido . ' ' . $persona->s_apellido;
        $this->ci = $persona->ci;
        $this->matricula = $persona->matricula_seguro;
        $this->complemento = $persona->complemento;
        $this->fecha_nacimiento = Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y');
        $this->edad = Carbon::parse($persona->fecha_nacimiento)->age;
        $this->sexo = $persona->sexo;
        $id_dept_exp = $persona->id_dept_exp;
        $id_civil = $persona->id_estado_civil;
        $id_tipo_asegurado = $persona->id_tipo_asegurado;

        $departamento = Departamento::find($id_dept_exp);
        if ($departamento) {
            $this->sigla = $departamento->sigla;
        } else {
            $this->sigla = null;
        }


        $estado_civil = EstadoCivil::find($id_civil);
        $this->estado_civil = $estado_civil->nombre;

        $tipo_asegurado = TipoAsegurado::find($id_tipo_asegurado);
        $this->tipo_asegurado_m = $tipo_asegurado->tipo_asegurado;

        $titular = Titular::where('id_persona', $data)
            ->where('estado', true)
            ->where('estado_vigencia', 'VIGENTE')
            ->first();

        $beneficiario = Beneficiario::where('id_persona', $data)
            ->where('estado', true)
            ->where('vigencia', 'VIGENTE')
            ->first();
        $estudiante = Estudiante::where('id_persona', $data)
            ->where('estado', true)
            ->where('estado_vigencia', true)
            //->where('etsado_validar', 'VALIDADO')
            ->first();
        if ($titular) {
            $this->foto = $this->obtenerImagenBase64($titular->foto_nombre);
        } elseif ($beneficiario) {
            $this->foto = $this->obtenerImagenBase64($beneficiario->foto_nombre);
        } elseif ($estudiante) {
            $this->foto = $this->obtenerImagenBase64($estudiante->foto_nombre);
        }

        //dd($titular , $beneficiario, $estudiante);
    }

    #[On('verAfiliado')]
    public function verAfiliado($data)
    {
        $this->idAfiliado = $data; // este es el idPersona
        $this->mostrarAfiliado($this->idAfiliado);
        $this->datosAfiliado($this->idAfiliado);
        $this->dispatch('modalVerAfiliado');
    }

    public function datosAfiliado($idAfiliado)
    {
        $estudiante = Estudiante::where('id_persona', $this->idAfiliado)
            ->with(['carreraEstudiante'])
            ->get();
        if ($estudiante->isNotEmpty()) {
            $this->varEstudiante = true;
            $this->fue_estudiante = $estudiante;
        } else {
            $this->varEstudiante = false;
            $this->fue_estudiante = 'no hay';
        }

        $historial = HistorialAsegurado::where('id_persona', $this->idAfiliado)
            ->with('tipoAseguradoHistorialAsegurado')
            ->get();

        $this->fue_beneficiario = [];
        $this->varBeneficiario = false;
        foreach ($historial as $key => $dato) {
            if (strpos($dato->tipoAseguradoHistorialAsegurado->tipo_asegurado, 'BENEFICIARIO') !== false) {
                $beneficiario = Beneficiario::with('beneficiarioConInstitucionBeneficiario.institucionBeneficiarioInstitucion')->find($dato->id_corresponde);
                if ($beneficiario) {
                    $this->varBeneficiario = true;
                    foreach ($beneficiario->beneficiarioConInstitucionBeneficiario as $institucion) {
                        $this->fue_beneficiario[] = [
                            'id' => $beneficiario->id,
                            'tipo_asegurado' => $dato->tipoAseguradoHistorialAsegurado->tipo_asegurado,
                            'fecha_afiliacion' => $beneficiario->fecha_afiliacion,
                            'fecha_reafiliacion' => $beneficiario->fecha_reafiliacion,
                            'fecha_baja' => $beneficiario->fecha_baja,
                            'remuneracion' => $beneficiario->con_remuneracion ? 'SI' : 'NO',
                            'institucion' => $institucion->institucionBeneficiarioInstitucion ? $institucion->institucionBeneficiarioInstitucion->nombre : 'No aplica',
                        ];
                    }
                    if ($beneficiario->beneficiarioConInstitucionBeneficiario->isEmpty()) {
                        $this->fue_beneficiario[]  = [
                            'id' => $beneficiario->id,
                            'tipo_asegurado' => $dato->tipoAseguradoHistorialAsegurado->tipo_asegurado,
                            'fecha_afiliacion' => $beneficiario->fecha_afiliacion,
                            'fecha_reafiliacion' => $beneficiario->fecha_reafiliacion,
                            'fecha_baja' => $beneficiario->fecha_baja,
                            'remuneracion' => $beneficiario->con_remuneracion ? 'SI' : 'NO',
                            'institucion' => 'No aplica',
                        ];
                    }
                }
            }
        }

        $this->fue_titular = [];
        $this->varTitular = false;
        $titular = Titular::with([
            'titularConTitularInstitucion.titularInstitucionConInstitucion',
            'titularPersona.contratoVoluntarioPersona'
        ])->where('id_persona', $this->idAfiliado)
            ->get();

        if ($titular->isNotEmpty()) {
            $this->varTitular = true;
            foreach ($titular as $t) {
                if ($t->tipo_titular) {
                    // Si tipo_titular es true
                    // dd( $tit->id);
                    $primeraInstitucion = TitularInstitucion::where('id_titular', $t->id)
                        //->where('tipo_institucion', 'PATRONAL')
                        // ->where('estado', true)
                        ->with([
                            'titularInstitucionConInstitucion',
                            'titularInstitucionConInstitucion.claseAportanteInstitucion',
                            'titularInstitucionConInstitucion.codigosInstitucion'
                        ])
                        ->orderBy('id', 'asc')
                        ->first();
                    //   dd($primeraInstitucion);
                    $this->fue_titular[] = [
                        'id' => $t->id,
                        'tipo_titular' => 'TITULAR INSTITUCIONAL',
                        'fecha_afiliacion' => $t->fecha_afiliacion,
                        'fecha_reafiliacion' => $t->fecha_reafiliacion,
                        'fecha_baja' => $t->fecha_baja,
                        'institucion' => $primeraInstitucion->titularInstitucionConInstitucion->nombre ?? 'No aplica',
                        'sigla'  => $primeraInstitucion->titularInstitucionConInstitucion->sigla ??  '',
                        'contrato' => 'No aplica',
                        'fecha_inicio' => null,
                        'fecha_fin' => null,
                    ];
                } else { // Si tipo_titular es false
                    $contrato = ContratoVoluntario::where('id_persona', $this->idAfiliado)
                        //  ->with('aporteVComprobante.contratoVoluntarioPersona')
                        ->where('estado', true)
                        ->orderBy('id', 'asc')
                        ->first();
                    //dd($contrato);
                    //$t->titularPersona->contratoVoluntarioPersona->first();
                    $this->fue_titular[] = [
                        'id' => $t->id,
                        'tipo_titular' => 'TITULAR VOLUNTARIO',
                        'fecha_afiliacion' => $t->fecha_afiliacion,
                        'fecha_reafiliacion' => $t->fecha_reafiliacion,
                        'fecha_baja' => $t->fecha_baja,
                        'institucion' => 'No aplica',
                        'sigla' => 'No aplica',
                        'contrato' => $contrato->descripcion_contrato ?? 'No aplica',
                        'fecha_inicio' => $contrato->fecha_inicio_contrato ?? null,
                        'fecha_fin' => $contrato->fecha_fin_contrato ?? null,
                    ];
                }
                //  dd($this->fue_titular);
            }
        }
    }

    #[On('GrupoFamiliarAfiliado')]
    public function GrupoFamiliarAfiliado($data)
    {
        $this->idAfiliado = $data;
        $this->mostrarAfiliado($data);
        $this->grupoFamiliarPer($data);
        $this->dispatch('modalGrupoFamiliarAfliado');
    }
    public $grupoFamiliarDatos;
    public function grupoFamiliarPer($idPer)
    {
        if ($idPer) {
            $FamiliaAfiliado = new GrupoFamiliarPersona();
            $datosFamiliaAfiliado = $FamiliaAfiliado->encontrarPersona($idPer);
            //dd($datosFamiliaAfiliado);
            $this->grupoFamiliarDatos = $datosFamiliaAfiliado;
            $this->count_grupo = count($this->grupoFamiliarDatos);
        } else {
            $this->grupoFamiliarDatos = [];
        }
    }

    private function obtenerImagenBase64($filename)
    {
        try {
            if ($filename) {
                $path = 'Afiliacion/FOTOS/' . $filename;
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
    private function obtenerImagenBase64Est($filename)
    {
        try {
            if ($filename) {
                $path = 'Afiliacion/SSUE/FOTOS/' . $filename;
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
    // Pdf historial
    #[On('pdfHistorial')]
    public function pdfHistorial($data)
    {
        $this->js('window.open("/afiliacion/afiliado/afiliado_historial_pdf/' . $data . '", "_blank")');
    }
    // Carnet historial
    #[On('carnetHistorial')]
    public function carnetHistorial($data)
    {
        dd('carnet historial', $data);
    }
    // Ficha afilaicion
    #[On('ficha_afiliacion')]
    public function ficha_afiliacion($data)
    {
        $this->js('window.open("/afiliacion/afiliado/ficha_afiliacion_pdf/' . $data . '", "_blank")');
    }
}
