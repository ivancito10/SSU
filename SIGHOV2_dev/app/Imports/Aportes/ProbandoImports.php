<?php

namespace App\Imports\Aportes;

use App\Models\Administracion\Persona;
use App\Models\Aportes\AporteInstitucionalTemp;
use App\Models\Aportes\Institucion;
use App\Models\Aportes\SalarioMinimoNacional;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class ProbandoImports implements ToCollection, WithCalculatedFormulas, SkipsEmptyRows
{
    protected $id_institucion;
    protected $id_mes;
    protected $id_tipo_aporte;
    protected $gestion;
    protected $porcentaje_aporte_dato;
    protected $umsa_state;
    protected $umsa_doc;
    protected $umsa_adm;
    protected $lote_comprobante;

    public function __construct($id_institucion, $id_mes, $id_tipo_aporte, $gestion, $porcentaje_aporte_dato,  $umsa_state, $lote_comprobante)
    {
        $this->id_institucion = $id_institucion;
        $this->id_mes = $id_mes;
        $this->id_tipo_aporte = $id_tipo_aporte;
        $this->gestion = $gestion;
        $this->porcentaje_aporte_dato = $porcentaje_aporte_dato;
        $this->umsa_state = $umsa_state;
        $this->lote_comprobante = $lote_comprobante;
    }

    public function collection(Collection $rows)
    {
        //DB::beginTransaction();

        //try {
            foreach ($rows as $key => $row) {
                if ($key == 0) {
                    continue;
                } else {

                    if ($row[0] === null && $row[1] === null && $row[2] === null && $row[3] === null && $row[4] === null) {
                        return null;
                    } else {
                        $reintegro = 0;
                        $beneficio = 0;
                        $planilla_adicional = 0;
                        $retroactivo = 0;

                        switch ($this->id_tipo_aporte) {
                            case '1':
                                $reintegro = 1;
                                break;
                            case '2':
                                $beneficio = 1;
                                break;
                            case '3':
                                $planilla_adicional = 1;
                                break;
                            case '4':
                                $retroactivo = 1;
                                break;
                        }

                        if($this->umsa_state == 1){
                            $this->umsa_doc = true;
                            $this->umsa_adm = null;
                            
                        }elseif($this->umsa_state == 2){
                            $this->umsa_doc = null;
                            $this->umsa_adm = true;

                        }elseif($this->umsa_state == 3){
                            $this->umsa_doc = null;
                            $this->umsa_adm = null;
                        }

                        //PARA EL ROW 0 P APELLIDO

                        if (trim($row[0]) === '' || $row[0] === null) {
                            $row[0] = null;
                        } else {
                            $row[0] = mb_strtoupper(trim($row[0]));
                        }

                        //PARA EL ROW 1 S APELLIDO

                        if (trim($row[1]) === '' || $row[1] === null) {
                            $row[1] = null;
                        } else {
                            $row[1] = mb_strtoupper(trim($row[1]));
                        }

                        if ($row[0] === null && $row[1] === null) {
                            throw new \Exception('Error en la fila: ' . $key + 1 . '. Debe registrar al menos un apellido.');
                        }

                        //PARA EL ROW 2 NOMBRES

                        if (trim($row[2]) === '' || $row[2] === null) {
                            throw new \Exception('Error en la fila: ' . $key + 1 . '. El campo NOMBRES es obligatorio. Verifique en manual de llenado de planillas.');
                        } else {
                            $row[2] = mb_strtoupper(trim($row[2]));
                        }

                        //PARA EL ROW 3 CARNET

                        if (trim($row[3]) === '' || $row[3] === null) {
                            throw new \Exception('Error en la fila: ' . $key + 1 . '. El campo NRO. DOCUMENTO es obligatorio. Verifique en manual de llenado de planillas.');
                        } else {
                            if (!is_numeric($row[3])) {
                                throw new \Exception('Error en la fila: ' . $key + 1 . '. El campo CARNET no es válido. Verifique en manual de llenado de planillas.');
                            } else {
                                $row[3] = trim($row[3]);
                            }
                        }

                        //PARA EL ROW 4 COMPLEMENTO

                        if (trim($row[4]) === '' || $row[4] === null) {
                            $row[4] = null;
                        } else {
                            $row[4] = mb_strtoupper(trim($row[4]));
                        }

                        //PARA EL ROW 5 TIPO DE DOCUMENTO

                        $tiposDocumentos = ['CEDULA DE IDENTIDAD', 'EXTRANJERO'];

                        if (trim($row[5]) === '' || $row[5] === null) {
                            throw new \Exception('Error en la fila: ' . $key + 1 . '. El campo TIPO DOCUMENTO es obligatorio. Verifique en manual de llenado de planillas.');
                        } else {
                                //dd(mb_strtoupper(trim($row[5])));
                            if (!in_array(mb_strtoupper(trim($row[5])), $tiposDocumentos)) {
                                throw new \Exception('Error en la fila: ' . $key + 1 . '. El campo TIPO DOCUMENTO no es válido. Verifique en manual de llenado de planillas.');
                            } else {
                                $row[5] = mb_strtoupper(trim($row[5]));
                            }
                        }

                        //PARA EL ROW 6 EXPEDIDO

                        $departamentos = ['LA PAZ', 'COCHABAMBA', 'SANTA CRUZ', 'ORURO', 'POTOSI', 'TARIJA', 'CHUQUISACA', 'BENI', 'PANDO', 'NINGUNO'];

                        if (trim($row[6]) === '' || $row[6] === null) {
                            throw new \Exception('Error en la fila: ' . $key + 1 . '. El campo EXPEDIDO es obligatorio. Verifique en manual de llenado de planillas.');
                        } else {
                            if (!in_array(mb_strtoupper(trim($row[6])), $departamentos)) {
                                throw new \Exception('Error en la fila: ' . $key + 1 . '. El campo EXPEDIDO no es válido. Verifique en manual de llenado de planillas.');
                            } else {
                                $row[6] = mb_strtoupper(trim($row[6]));
                            }
                        }

                        //PARA EL ROW 7 ITEM

                        if (trim($row[7]) === '' || $row[7] === null) {
                            $row[7] = null;
                        } else {
                            $row[7] = mb_strtoupper(trim($row[7]));
                        }

                        //PARA EL ROW 8 CARGO

                        if (trim($row[8]) === '' || $row[8] === null) {
                            $row[8] = null;
                        } else {
                            $row[8] = mb_strtoupper(trim($row[8]));
                        }

                        //PARA EL ROW 9 AREA

                        if (trim($row[9]) === '' || $row[9] === null) {
                            $row[9] = null;
                        } else {
                            $row[9] = mb_strtoupper(trim($row[9]));
                        }

                        //PARA EL ROW 10 CARGA HORARIA

                        if (trim($row[10]) === '' || $row[10] === null) {
                            $row[10] = null;
                        } else {
                            if (!is_numeric($row[10]) && $row[10] > 999) {
                                throw new \Exception('Error en la fila: ' . $key + 1 . '. El campo CARGA HORARIA no es válido. Verifique en manual de llenado de planillas.');
                            }
                        }

                        //PARA EL ROW 11 TIPO TRABAJADOR

                        $tiposTrabajador = ['EVENTUAL', 'PERMANENTE'];

                        if (trim($row[11] === '') || $row[11] === null) {
                            throw new \Exception('Error en la fila: ' . $key + 1 . '. El campo TIPO TRABAJADOR es obligatorio. Verifique en manual de llenado de planillas.');
                        } else {
                            if (!in_array(mb_strtoupper(trim($row[11])), $tiposTrabajador)) {
                                throw new \Exception('Error en la fila: ' . $key + 1 . '. El campo TIPO TRABAJADOR no es válido. Verifique en manual de llenado de planillas.');
                            } else {
                                $row[11] = mb_strtoupper(trim($row[11]));
                            }
                        }

                        //PARA EL ROW 12 TOTAL GANADO

                        if (trim($row[12] === '') || $row[12] === null) {
                            throw new \Exception('Error en la fila: ' . $key + 1 . '. El campo TOTAL GANADO es obligatorio. Verifique en manual de llenado de planillas.');
                        } else {
                            if (!is_numeric($row[12])) {
                                throw new \Exception('Error en la fila: ' . $key + 1 . '. El campo TOTAL GANADO no es válido. Verifique en manual de llenado de planillas.');
                            }
                        }

                        //PARA EL ROW 13 APORTE

                        if (trim($row[13] === '') || $row[13] === null) {
                            throw new \Exception('Error en la fila: ' . $key + 1 . '. El campo APORTE es obligatorio. Verifique en manual de llenado de planillas.');
                        } else {
                            if (!is_numeric($row[13])) {
                                throw new \Exception('Error en la fila: ' . $key + 1 . '. El campo APORTE no es válido. Verifique en manual de llenado de planillas.');
                            }
                        }

                        //PARA EL ROW 14 DIAS TRABAJADOS

                        if (trim($row[14] === '') || $row[14] === null) {
                            throw new \Exception('Error en la fila: ' . $key + 1 . '. El campo DIAS TRABAJADOS es obligatorio. Verifique en manual de llenado de planillas.');
                        } else {
                            if (!is_numeric($row[14]) || $row[14] > 30 || $row[14] < 1) {
                                throw new \Exception('Error en la fila: ' . $key + 1 . '. El campo DIAS TRABAJADOS no es válido. Verifique en manual de llenado de planillas.');
                            }
                        }

                        //PARA EL ROW 15 JUBILADO

                        if (trim($row[15] === '') || $row[15] === null) {
                            throw new \Exception('Error en la fila: ' . $key + 1 . '. El campo JUBILADO es obligatorio. Verifique en manual de llenado de planillas.');
                        } else {
                            if (mb_strtoupper(trim($row[15])) === 'SI' || mb_strtoupper(trim($row[15])) === 'NO') {
                                $row[15] = mb_strtoupper(trim($row[15]));
                            } else {
                                throw new \Exception('Error en la fila: ' . $key + 1 . '. El campo JUBILADO no es válido. Verifique en manual de llenado de planillas.');
                            }
                        }

                        //PARA EL ROW 16 FECHA INGRESO

                        if (trim($row[16] === '') || $row[16] === null) {
                            throw new \Exception('Error en la fila: ' . $key + 1 . '. El campo FECHA DE INGRESO es obligatorio. Verifique en manual de llenado de planillas.');
                        } else {
                            if (is_numeric($row[16])) {
                                $row[16] = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[16]))->format('Y-m-d');
                            } else {
                                if (Carbon::hasFormat($row[16], 'd/m/Y')) {
                                    $row[16] = Carbon::createFromFormat('d/m/Y', $row[16])->format('Y-m-d');
                                    //$row[16] = Carbon::CREA($row[16])->format('Y-m-d');
                                } else {
                                    throw new \Exception('Error en la fila: ' . $key + 1 . '. El campo FECHA DE INGRESO no es válido. Verifique en manual de llenado de planillas.');
                                }
                            }
                        }

                        //PARA EL ROW 17 FECHA NOVEDAD RETIRO O SUSPENSION

                        if (trim($row[17]) === '' || $row[17] === null) {
                            $row[17] = null;
                        } else {
                            if (is_numeric($row[17])) {
                                $row[17] = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[17]))->format('Y-m-d');
                            } else {
                                if (Carbon::hasFormat($row[17], 'd/m/Y')) {
                                    $row[17] = Carbon::createFromFormat('d/m/Y', $row[17])->format('Y-m-d');
                                    //$row[17] = Carbon::parse($row[17])->format('Y-m-d');
                                } else {
                                    throw new \Exception('Error en la fila: ' . $key + 1 . '. El campo FECHA DE NOVEDAD DE RETIRO O SUSPENSIÓN no es válido. Verifique en manual de llenado de planillas.');
                                }
                            }
                        }

                        //PARA EL ROW 18 MOTIVO DE RETIRO O SUSPENSION TEMPORAL

                        $novedades = ['R', 'C', 'S', 'F', 'O', 'I'];

                        if (trim($row[18]) === '' || $row[18] === null) {
                            $row[18] = null;
                        } else {
                            if (!in_array(mb_strtoupper(trim($row[18])), $novedades)) {
                                throw new \Exception('Error en la fila: ' . $key + 1 . '. El campo MOTIVO DE RETIRO O SUSPENSIÓN TEMPORAL no es válido. Verifique en manual de llenado de planillas.');
                            } else {
                                $row[18] = mb_strtoupper(trim($row[18]));
                            }
                        }

                        if ($row[17] !== null && $row[18] === null) {
                            throw new \Exception('Error en la fila: ' . $key + 1 . '. Si introdujo fecha de novedad debe especificar el motivo de novedad. Verifique en manual de llenado de planillas.');
                        }

                        //PARA EL ROW 19 COMPENSACIONES DE APORTES DE SALARIOS MENORES AL MINIMO NACIONAL

                        if (trim($row[19] === '') || $row[19] === null) {
                            $row[19] = null;
                        } else {
                            if (!is_numeric($row[19])) {
                                throw new \Exception('Error en la fila: ' . $key + 1 . '. El campo COMPENSACION DE APORTES DE SALARIOS MENORES AL MÍNIMO NACIONAL no es válido. Verifique en manual de llenado de planillas.');
                            }
                        }

                        //PARA EL ROW 20 PARENTESCO

                        $tiposParentesco = ['TITULAR', 'DERECHOHABIENTE'];

                        if (trim($row[20] === '') || $row[20] === null) {
                            $row[20] = null;
                        } else {
                            if (!in_array(mb_strtoupper(trim($row[20])), $tiposParentesco)) {
                                throw new \Exception('Error en la fila: ' . $key + 1 . '. El campo PARENTESCO no es válido. Verifique en manual de llenado de planillas.');
                            } else {
                                $row[20] = mb_strtoupper(trim($row[20]));
                            }
                        }

                        //PARA EL ROW 21 TIPO DE RENTA

                        $tiposRenta = ['JUBILACION', 'MUERTE', 'INVALIDEZ', 'IVM', 'RP'];

                        if (trim($row[21]) === '' || $row[21] === null) {
                            $row[21] = null;
                        } else {
                            if (!in_array(mb_strtoupper(trim($row[21])), $tiposRenta)) {
                                throw new \Exception('Error en la fila: ' . $key + 1 . '. El campo TIPO DE RENTA no es válido. Verifique en manual de llenado de planillas.');
                            } else {
                                $row[21] = mb_strtoupper(trim($row[21]));
                            }
                        }

                        //FIN DEL TRATAMIENTO DE DATOS---------------------------------------------------------------------------------------------

                        $idPersona = 0;
                        $personaAfiliada = false;

                        $validarPersona = Persona::where('ci', $row[3])
                            ->where('complemento', $row[4])
                            ->first();

                        if ($validarPersona) {
                            $idPersona = $validarPersona->id;
                            if ($validarPersona->afiliado) {
                                $personaAfiliada = true;
                            }
                        } else {
                            $clave_unica = $row[3] . $row[4];

                            $nuevaPersona = Persona::create([
                                'p_apellido' => $row[0],
                                's_apellido' => $row[1],
                                'nombres' => $row[2],
                                'ci' => $row[3],
                                'complemento' => $row[4],
                                'fecha_nacimiento' => null,
                                'estado_asuss' => false,
                                'afiliado' => false,
                                'id_tipo_asegurado' => 0,
                                'clave_unica' => $clave_unica,
                                'id_user_created' => auth()->user()->id,
                            ]);

                            if ($nuevaPersona) {
                                $idPersona = $nuevaPersona->id;
                            }
                        }

                        if ($idPersona != 0) {

                            $ganado_por_dia = $row[12] / $row[14];
                            $total_aporte = round($row[12] * $this->porcentaje_aporte_dato, 2);

                            $jubilado = false;
                            if ($row[15] == 'SI') {
                                $jubilado = true;
                            }

                            $salarioMinimo = SalarioMinimoNacional::where('estado', true)->orderBy('id', 'desc')->first();

                            $claseAportante = Institucion::with(['claseAportanteInstitucion'])->find($this->id_institucion);

                            $estado_compensacion = false;

                            if ($claseAportante->claseAportanteInstitucion->nombre != 'RENTISTA') {
                                if (($total_aporte < ($salarioMinimo->monto * 0.10)) && ($row[14] === 30)) {
                                    $estado_compensacion = true;
                                }
                            }

                            $nuevoAporteTemp = AporteInstitucionalTemp::create([
                                'id_persona' => $idPersona,
                                'id_institucion' => $this->id_institucion,
                                'item' => $row[7],
                                'cargo' => $row[8],
                                'area' => $row[9],
                                'carga_horaria' => $row[10],
                                'tipo_trabajador' => $row[11],
                                'dias_trabajados' => $row[14],
                                'total_ganado' => $row[12],
                                'total_ganado_dia' => $ganado_por_dia,
                                'total_aporte' => $total_aporte,
                                'gestion' => $this->gestion,
                                'reintegro_normal' => 0,
                                'id_mes' => $this->id_mes,
                                'reintegro_incr_salarial' => $reintegro,
                                'beneficio_social' => $beneficio,
                                'planilla_adicional' => $planilla_adicional,
                                'afiliado' => $personaAfiliada,
                                'jubilado' => $jubilado,
                                'fecha_ingreso' => $row[16],
                                'fecha_novedad_retiro_suspension' => $row[17],
                                'motivo_novedad_retiro_suspension' => $row[18],
                                'parentesco_rentista' => $row[20],
                                'tipo_renta' => $row[21],
                                'aporte_planilla' => $row[13],
                                'compensacion_salario_minimo' => $row[19],
                                'estado_compensacion' => $estado_compensacion,
                                'retroactivo' => $retroactivo,
                                'umsa_doc' => $this->umsa_doc,
                                'umsa_adm' => $this->umsa_adm,
                                'lote_comp' => $this->lote_comprobante,
                                'id_user_created' => auth()->user()->id,
                            ]);

                            if ($nuevoAporteTemp) {
                            } else {
                                throw new \Exception('No se registró el aporte');
                            }
                        } else {
                            throw new \Exception('Error al registrar a la persona');
                        }
                    }



                    //dd($row, 'esta es la fila: ', $key);
                    /*if(is_numeric($row[16])){
                        dd(Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[16]))->format('Y-m-d'));
                    } else {
                        dd(Carbon::parse($row[16])->format('Y-m-d'));
                    }
                    $value = str_replace(',', '.', $row[13]);
                    $floatValue = (float)$value;
                    $formattedValue = number_format($floatValue, 2, '.', '');*/
                }
            }
            //DB::commit();
        /*} catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }*/
    }
}
