<?php

namespace App\Imports\Aportes;

use App\Models\Aportes\AporteInstitucionalTemp;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Administracion\Persona;
use App\Models\Aportes\SalarioMinimoNacional;
use App\Models\Aportes\Institucion;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Services\ValidarPersona;
use App\Services\ConexionAsuss;
use App\Models\Administracion\AccesoAsussToken;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


use Carbon\Carbon;

class AportesPlanillasImport implements ToModel, WithStartRow, WithBatchInserts, SkipsEmptyRows
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public $id_institucion;
    public $id_mes;
    public $id_tipo_aporte;
    public $gestion;
    public $reintegro;
    public $beneficio;
    public $id_persona;
    public $porcentaje_aporte_dato;
    public $ci;
    public $complemento;
    public $p_apellido;
    public $s_apellido;
    public $nombres;
    public $fecha_nacimiento;
    public $estado_asuss;
    public $planilla_adicional;
    public $retroactivo;
    public $jubilado, $tipo_trabajador, $item, $cargo = '', $area = '', $carga_horaria, $total_ganado, $aporte_planilla, $dias_trabajados,
        $motivo_novedad_retiro_suspension, $compensacion_salario_minimo, $parentesco_rentista, $tipo_renta;
        public $tipo_documento,$extranjero;

    public $tokenBd;

    public function __construct($id_institucion, $id_mes, $id_tipo_aporte, $gestion, $porcentaje_aporte_dato)
    {
 
        $this->id_institucion = $id_institucion;
        $this->id_mes = $id_mes;
        $this->id_tipo_aporte = $id_tipo_aporte;
        $this->gestion = $gestion;
        //$this->tokenBd = AccesoAsussToken::orderby('id', 'desc')->first();
        $this->porcentaje_aporte_dato = $porcentaje_aporte_dato;
        //dd($porcentaje_aporte_dato);
        //dd($this->id_tipo_aporte, '+2');
    }
    public function startRow(): int
    {
        // Indica que la importación debe comenzar desde la segunda fila (índice 2)
        return 2;
    }
    public function batchSize(): int
    {
        return 100;
    }
    public function model(array $row)
    {
        //dd($this->id_tipo_aporte, '+2');
        DB::beginTransaction();
        try {

            // if (array_key_exists(3, $row) && trim($row[3]) !== '') {
            //dd($row[3]);

            if ($row[0] == null && $row[1] == null && $row[2] == null && $row[3] == null) {
            } else {
                if ($this->id_tipo_aporte == '0') {
                    $this->reintegro = false;
                    $this->beneficio = false;
                    $this->planilla_adicional = false;
                    $this->retroactivo = false;
                }
                if ($this->id_tipo_aporte == '1') {
                    $this->reintegro = true;
                    $this->beneficio = false;
                    $this->planilla_adicional = false;
                    $this->retroactivo = false;
                }
                if ($this->id_tipo_aporte == '2') {
                    $this->reintegro = false;
                    $this->beneficio = true;
                    $this->planilla_adicional = false;
                    $this->retroactivo = false;
                }
                if ($this->id_tipo_aporte == '3') {
                    $this->reintegro = false;
                    $this->beneficio = false;
                    $this->planilla_adicional = true;
                    $this->retroactivo = false;
                }
                if ($this->id_tipo_aporte == '4') {
                    $this->reintegro = false;
                    $this->beneficio = false;
                    $this->planilla_adicional = false;
                    $this->retroactivo = true;
                }

                $parametros = [
                    'ci' => $row[3],
                    'complemento' => (isset($row[4]) && trim($row[4]) !== '') ? trim($row[4]) : null, // Si complemento es una cadena vacía, se asigna null
                ];

                $persona_validada = Persona::where('ci', $parametros['ci'])
                    ->where('complemento', $parametros['complemento'])
                    ->first();

                if ($persona_validada) {
                    $this->id_persona = $persona_validada->id;
                    //dd($row);
                } else {
                    //dd($row);
                    // Si la persona no existe, se crea una nueva
                    $this->p_apellido = $row[0] ? trim($row[0]) : null;
                    $this->s_apellido = $row[1] ? trim($row[1]) : null;
                    $this->nombres = $row[2] ? trim($row[2]) : null;
                    $this->ci = $row[3] ? trim($row[3]) : null;
                    $this->complemento = $row[4] ? trim($row[4]) : null;
                    $this->fecha_nacimiento = null;
                    $this->estado_asuss = 0;
                    $this->tipo_documento=$row[5] ? trim($row[5]) : null;
                    if($this->tipo_documento==='EXTRANJERO')
                    {
                        $this->extranjero=1;
                    }
                    else
                    {
                        $this->extranjero=0;
                    }

                    $clave_unica = $this->complemento ? $this->ci . $this->complemento : $this->ci;
                    
                    $persona = Persona::create([
                        'p_apellido' => $this->p_apellido,
                        's_apellido' => $this->s_apellido,
                        'nombres' => $this->nombres,
                        'ci' => $this->ci,
                        'complemento' => $this->complemento,
                        'fecha_nacimiento' => $this->fecha_nacimiento,
                        'estado_asuss' => $this->estado_asuss,
                        'afiliado' => false,
                        'id_tipo_asegurado' => 0,
                        'extranjero' => $this->extranjero,
                        'clave_unica' => $clave_unica,
                        'id_user_created' => auth()->user()->id,
                    ]);

                    $this->id_persona = $persona->id;
                }

                $ganado_por_dia = $row[12] / $row[14];
                $total_aporte = $row[12] * $this->porcentaje_aporte_dato;
                if ($row[15] == 'SI') {
                    $this->jubilado = true;
                } elseif ($row[15] == 'NO') {
                    $this->jubilado = false;
                }
                if ($row[7] != null) {
                    $this->item = $row[7];
                }
                if ($row[8] != null) {
                    $this->cargo = $row[8];
                }
                if ($row[9] != null) {
                    $this->area = $row[9];
                }
                if ($row[10] != null) {
                    $this->carga_horaria = $row[10];
                }else{
                    $this->carga_horaria = null;
                }
                if ($row[11] != null) {
                    $this->tipo_trabajador = $row[11];
                }
                if ($row[12] != null) {
                    $this->total_ganado = $row[12];
                }
                if ($row[13] != null) {
                    $this->aporte_planilla = $row[13];
                }
                if ($row[14] != null) {
                    $this->dias_trabajados = $row[14];
                }
                if ($row[18] != null) {
                    $this->motivo_novedad_retiro_suspension = $row[18];
                }else{
                    $this->motivo_novedad_retiro_suspension = null;
                }
                if ($row[19] == null) {
                    $this->compensacion_salario_minimo = 0;
                }else{
                    $this->compensacion_salario_minimo = $row[19];
                }
                if ($row[20] != null) {
                    $this->parentesco_rentista = $row[20];
                }else{
                    $this->parentesco_rentista = null;
                }
                if ($row[21] != null) {
                    $this->tipo_renta = $row[21];
                }else{
                    $this->tipo_renta = null;
                }

                if ($row[16] != null) {
                    $fecha_ingreso = Carbon::createFromFormat('d/m/Y', $row[16])->format('Y-m-d');
                }

                $fecha_novedad_retiro_suspension = null;
                if ($row[17] != null) {
                    $fecha_novedad_retiro_suspension = Carbon::createFromFormat('d/m/Y', $row[17])->format('Y-m-d');
                }else{
                    $fecha_novedad_retiro_suspension = null;
                }

                $salarioMinimo = SalarioMinimoNacional::where('estado', true)->orderBy('id', 'desc')->first();

                $claseAp = Institucion::where('id', $this->id_institucion)->with('claseAportanteInstitucion')->first();

                $estado_compensacion = false;

                if (($total_aporte < ($salarioMinimo->monto * 0.10)) && ($claseAp->claseAportanteInstitucion->nombre != 'RENTISTA')) {
                    $estado_compensacion = true;
                }

                $institucion = AporteInstitucionalTemp::create([
                    'id_persona' => $this->id_persona,
                    'id_institucion' => $this->id_institucion,
                    'item' => $this->item,
                    'cargo' => $this->cargo,
                    'area' => $this->area,
                    'carga_horaria' => $this->carga_horaria,
                    'tipo_trabajador' => $this->tipo_trabajador,
                    'dias_trabajados' => $this->dias_trabajados,
                    'total_ganado' => $this->total_ganado,
                    'total_ganado_dia' => $ganado_por_dia,
                    'total_aporte' => $total_aporte,
                    'gestion' => $this->gestion,
                    'reintegro_normal' => 0,
                    'id_mes' => $this->id_mes,
                    'reintegro_incr_salarial' => $this->reintegro,
                    'beneficio_social' => $this->beneficio,
                    'planilla_adicional' => $this->planilla_adicional,
                    'retroactivo' => $this->retroactivo,
                    'afiliado' => false,
                    'jubilado' => $this->jubilado,
                    'fecha_ingreso' => $fecha_ingreso,
                    'fecha_novedad_retiro_suspension' => $fecha_novedad_retiro_suspension,
                    'motivo_novedad_retiro_suspension' => $this->motivo_novedad_retiro_suspension,
                    'parentesco_rentista' => $this->parentesco_rentista,
                    'tipo_renta' => $this->tipo_renta,
                    'aporte_planilla' => $this->aporte_planilla,
                    'compensacion_salario_minimo' => $this->compensacion_salario_minimo,
                    'estado_compensacion' => $estado_compensacion,
                    'id_user_created' => auth()->user()->id,
                ]);
                

                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            //dd('Error processing row: ' . json_encode($row) . ' - Error: ' . $e->getMessage());
            return $e->getMessage();
            //dd('Error processing row: ' . json_encode($row) . ' - Error: ' . $e->getMessage());
        }
    }
}
