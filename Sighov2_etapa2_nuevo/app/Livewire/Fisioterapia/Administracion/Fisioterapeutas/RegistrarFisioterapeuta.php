<?php

namespace App\Livewire\Fisioterapia\Administracion\Fisioterapeutas;

use App\Http\Controllers\Fisioterapia\CredencialesFisioterapeutaController;
use App\Services\Fisioterapia\NestEtapa1Client;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;

class RegistrarFisioterapeuta extends Component
{
    public string $ci = '', $complemento = '';
    #[Locked] public ?array $documento = null;
    #[Locked] public ?int $idPersona = null;
    #[Locked] public array $catalogos = [];
    #[Locked] public bool $guardado = false;
    public array $f = [];

    public function boot(): void
    {
        Gate::authorize('fisioterapia.administrar');
    }

    public function buscar(NestEtapa1Client $api): void
    {
        $this->reset(['idPersona', 'documento', 'catalogos', 'f']);
        $this->resetValidation();
        $this->ci = strtoupper(trim($this->ci));
        $this->complemento = strtoupper(trim($this->complemento));
        $this->validate(['ci' => ['required','regex:/^[a-z0-9]{1,30}$/i'], 'complemento' => ['nullable','regex:/^[a-z0-9]{1,10}$/i']]);
        try {
            $p = $api->personaParaIncorporar($this->ci, $this->complemento);
            $this->f = [
                'nombres' => $p['nombres'] ?? '', 'primerApellido' => $p['p_apellido'] ?? '',
                'segundoApellido' => $p['s_apellido'] ?? '', 'sexo' => $p['sexo'] ?? 'M',
                'fechaNacimiento' => $p['fechaNacimiento'] ?? '', 'tipoDocumento' => $p['tipoDocumento'] ?? '',
                'idDepartamento' => data_get($p,'departamentoNacimiento.id'), 'idProvincia' => data_get($p,'provinciaNacimiento.id'),
                'idNacimientoMunicipio' => $p['idNacimientoMunicipio'] ?? null, 'idDeptExp' => $p['idDeptExp'] ?? null,
                'idEstadoCivil' => $p['idEstadoCivil'] ?? null, 'tipoContrato' => 'EVENTUAL',
                'gradoAcademico' => '', 'fechaContratoInicio' => '', 'fechaContratoFin' => '',
            ];
            $this->catalogos = $api->catalogosRegistro($this->f['idDepartamento'], $this->f['idProvincia']);
            $this->idPersona = $p['idPersona'] ?? null;
            $respuesta = $api->obtener('especialistas/persona', $this->idPersona);
            if ($respuesta !== null) {
                $especialista = $respuesta['especialista'];
                $tipo = $especialista['tipoContrato'] ?? '';
                $this->f['tipoContrato'] = $tipo;
                $this->f['gradoAcademico'] = mb_strtoupper(trim($especialista['gradoAcademico'] ?? ''));
                if ($tipo === 'EVENTUAL') {
                    $this->f['fechaContratoInicio'] = $especialista['contratoDesde'] ?? '';
                    $this->f['fechaContratoFin'] = $especialista['contratoHasta'] ?? '';
                }
            }
            $this->documento = ['ci' => $this->ci, 'complemento' => $this->complemento];
        } catch (\Throwable $e) {
            $this->fallo($e);
        }
    }

    public function sinDepartamentoNacimiento(): bool
    {
        $departamento = collect($this->catalogos['departamentos'] ?? [])
            ->firstWhere('id', $this->f['idDepartamento'] ?? null);
        return strtoupper(trim($departamento['nombre'] ?? '')) === 'NINGUNO';
    }

    public function updatedF($valor, $campo): void
    {
        if ($campo === 'tipoContrato' && $valor === 'PERMANENTE') {
            $this->f['fechaContratoInicio'] = $this->f['fechaContratoFin'] = '';
        }
        if (!in_array($campo, ['idDepartamento', 'idProvincia'], true)) return;
        if ($campo === 'idDepartamento') {
            $this->f['idProvincia'] = null;
            $this->catalogos['provincias'] = [];
        }
        $this->f['idNacimientoMunicipio'] = null;
        $this->catalogos['municipios'] = [];
        if ($this->sinDepartamentoNacimiento()) {
            $this->resetValidation(['f.idProvincia', 'f.idNacimientoMunicipio']);
            return;
        }
        try {
            $this->catalogos = app(NestEtapa1Client::class)->catalogosRegistro($this->f['idDepartamento'], $this->f['idProvincia']);
        } catch (\Throwable $e) {
            $this->fallo($e);
        }
    }

    public function matricula(): string
    {
        $f = $this->f;
        $fecha = $f['fechaNacimiento'] ?? '';
        if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $fecha, $m) || !checkdate((int)$m[2], (int)$m[3], (int)$m[1])) return '';
        $letras = fn ($campo) => preg_replace('/[^\p{L}]/u', '', mb_strtoupper($f[$campo] ?? ''));
        $p = $letras('primerApellido');
        $s = $letras('segundoApellido');
        $n = $letras('nombres');
        $iniciales = $p && $s ? mb_substr($p,0,1).mb_substr($s,0,1) : mb_substr($p.$s,0,2);
        if (mb_strlen($iniciales) < 2 || !$n) return '';
        return substr($m[1],2).sprintf('%02d',(int)$m[2]+(($f['sexo'] ?? '') === 'F' ? 50 : 0)).$m[3].$iniciales.mb_substr($n,0,1);
    }

    private function datos(): ?array
    {
        if ($this->guardado || !$this->documento || $this->documento !== ['ci'=>$this->ci,'complemento'=>$this->complemento]) {
            $this->addError('ci','Busque el documento antes de guardar.');
            return null;
        }
        $reglas = [
            'f.nombres'=>'required|string|max:100', 'f.primerApellido'=>'required_without:f.segundoApellido|nullable|string|max:100',
            'f.segundoApellido'=>'nullable|string|max:100','f.sexo'=>'required|in:M,F',
            'f.fechaNacimiento'=>'required|date_format:Y-m-d|before_or_equal:today',
            'f.tipoDocumento'=>'required|in:NACIONAL,EXTRANJERO', 'f.tipoContrato'=>'required|in:EVENTUAL,PERMANENTE',
            'f.gradoAcademico'=>'required|in:DOCTOR,DOCTORA,LICENCIADO,LICENCIADA',
        ];
        foreach (['idEstadoCivil'=>'estadosCiviles','idDeptExp'=>'expedidos','idDepartamento'=>'departamentos',
            'idProvincia'=>'provincias','idNacimientoMunicipio'=>'municipios'] as $campo=>$lista) {
            if ($this->sinDepartamentoNacimiento() && in_array($campo, ['idProvincia', 'idNacimientoMunicipio'], true)) continue;
            $reglas['f.'.$campo] = ['required','integer',Rule::in(array_column($this->catalogos[$lista] ?? [],'id'))];
        }
        if (($this->f['tipoContrato'] ?? '') === 'EVENTUAL') {
            $reglas['f.fechaContratoInicio']='required|date_format:Y-m-d';
            $reglas['f.fechaContratoFin']='required|date_format:Y-m-d|after_or_equal:f.fechaContratoInicio';
        }
        $f = $this->validate($reglas)['f'];
        if (!$this->matricula()) {
            $this->addError('f.primerApellido','Revise los nombres y apellidos. Un apellido único debe tener al menos dos letras.');
            return null;
        }
        foreach (['nombres', 'primerApellido', 'segundoApellido'] as $campo) {
            if (isset($f[$campo])) $f[$campo] = mb_strtoupper($f[$campo], 'UTF-8');
        }
        $f['esExtranjero'] = $f['tipoDocumento'] === 'EXTRANJERO';
        $f['idDepartamentoNacimiento'] = (int) $f['idDepartamento'];
        $f['idNacimientoMunicipio'] = $this->sinDepartamentoNacimiento() ? null : (int) $f['idNacimientoMunicipio'];
        unset($f['tipoDocumento'],$f['idDepartamento'],$f['idProvincia']);
        foreach (['idEstadoCivil','idDeptExp'] as $campo) $f[$campo]=(int)$f[$campo];
        if ($this->idPersona) $f['idPersona']=$this->idPersona;
        return $this->documento + $f;
    }

    public function validar(): void
    {
        if ($this->datos()) $this->dispatch('confirmar-incorporacion');
    }

    public function guardar(NestEtapa1Client $api): void
    {
        if (!($datos = $this->datos())) return;
        try {
            $r = $api->incorporar($datos);
        } catch (\Throwable $e) {
            $this->fallo($e);
            return;
        }
        $this->guardado = true;
        $pdf = null;
        $aviso = null;
        if (($r['usuarioCreado'] ?? false) === true) {
            try {
                if (empty($r['idUsuario']) || !filled($r['email'] ?? null) || !filled($r['passwordTemporal'] ?? null)) {
                    throw new \UnexpectedValueException();
                }
                $pdf = CredencialesFisioterapeutaController::preparar($r, $datos);
            } catch (\Throwable $e) {
                $aviso = 'La cuenta fue creada, pero no se pudo preparar el documento de credenciales.';
            }
        }
        $this->dispatch('incorporacion-guardada', pdf: $pdf, aviso: $aviso);
    }

    private function fallo(\Throwable $e): void
    {
        $mensaje = 'No se pudo completar la operación con etapa 1. Verifique la conexión y vuelva a consultar.';
        if ($e instanceof RequestException && in_array($e->response->status(), [400,404,409,422], true)) {
            $mensaje = implode(' ', (array) ($e->response->json('message') ?? $mensaje));
        } elseif ($e instanceof \DomainException) {
            $mensaje = $e->getMessage();
        }
        $this->addError('registro', $mensaje);
    }

    public function cancelarRegistro(): void
    {
        $this->redirectRoute('fisioterapia.administracion.fisioterapeutas.index');
    }

    public function render()
    {
        return view('livewire.fisioterapia.administracion.fisioterapeutas.registrar-fisioterapeuta');
    }
}
