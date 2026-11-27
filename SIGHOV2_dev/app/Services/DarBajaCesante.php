<?php

namespace App\Services;

use Illuminate\Support\Str;

use App\Services\GrupoFamiliarPersona;
use App\Models\Administracion\Persona;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\BeneficiarioInstitucion;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Aportes\Mes;
use App\Models\Vigencia\BajaInstitucional;
use App\Models\Vigencia\HistorialAsegurado;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DarBajaCesante
{

    public function bajaDefinitiva($idPersona, $idInstitucion, $fechaBaja, $motivoBaja, $idMes, $gestion)
    {
        //dd($idPersona, $idInstitucion, $fechaBaja, $motivoBaja, 'prueba desde el servicio');
        DB::beginTransaction();
        try {
            $mes = Mes::find($idMes);

            $persona = Persona::where('id', $idPersona)->with(['tipoAseguradoPersona'])->first();

            if ($persona->tipoAseguradoPersona->tipo_asegurado == 'TITULAR') {
                $titular = Titular::where('id_persona', $persona->id)->orderBy('id', 'desc')->first();

                if ($titular) {
                    $titularInstitucion = TitularInstitucion::where('id_titular', $titular->id)->where('id_institucion', $idInstitucion)->first();

                    if ($titularInstitucion) {
                        $titularInstitucion->estado = false;
                        $titularInstitucion->id_user_updated = auth()->user()->id;
                        $titularInstitucion->save();

                        $masInstituciones = TitularInstitucion::where('id_titular', $titular->id)->where('estado', true)->get();
                        if (count($masInstituciones) < 1) {
                            $fechaBajaDefinitiva = $this->calcularCesantia($fechaBaja);

                            if (Carbon::parse($fechaBajaDefinitiva)->greaterThan(Carbon::now())) {
                                $bajaInstitucional = BajaInstitucional::create([
                                    'fecha_baja_inst' => $fechaBaja,
                                    'fecha_baja_ssu' => $fechaBajaDefinitiva,
                                    'id_persona' => $idPersona,
                                    'descripcion_motivo' => $motivoBaja . ' (BAJA POR PLANILLA CORRESPONDIENTE AL MES DE ' . $mes->mes . ' DE LA GESTIÓN ' . $gestion,
                                    'baja_definitiva' => false,
                                    'id_user_created' => auth()->user()->id,
                                ]);

                                $titular->estado_vigencia = 'CESANTE';
                                $titular->fecha_baja = $fechaBajaDefinitiva;
                                $titular->id_user_updated = auth()->user()->id;
                                $titular->save();

                                $actualizacionHistorial = HistorialAsegurado::where('id_tipo_asegurado', $persona->id_tipo_asegurado)
                                    ->where('id_corresponde', $titular->id)
                                    ->where('id_persona', $idPersona)
                                    ->first();

                                if ($actualizacionHistorial) {
                                    $actualizacionHistorial->estado_vigencia = 'CESANTE';
                                    $actualizacionHistorial->fecha_cesantia = $fechaBaja;
                                    $actualizacionHistorial->fecha_baja = $fechaBajaDefinitiva;
                                    $actualizacionHistorial->id_user_updated = auth()->user()->id;
                                    $actualizacionHistorial->save();
                                }

                                //PARA EL GRUPO FAMILIAR 

                                $servicioGrupoFamiliar = new GrupoFamiliarPersona();

                                $listadoFamiliares = $servicioGrupoFamiliar->encontrarPersona($idPersona);

                                if ($listadoFamiliares != []) {
                                    if (count($listadoFamiliares) > 1) {
                                        foreach ($listadoFamiliares as $familiar) {
                                            if ($familiar['id'] != $idPersona) {

                                                $actualizarBeneficiario = Beneficiario::where('id_persona', $familiar['id'])
                                                    ->where('id_titular', $titular->id)
                                                    ->orderBy('id', 'desc')
                                                    ->first();
                                                if ($actualizarBeneficiario) {
                                                    $actualizarBeneficiario->vigencia = 'CESANTE';
                                                    $actualizarBeneficiario->fecha_baja = $fechaBajaDefinitiva;
                                                    $actualizarBeneficiario->id_user_updated = auth()->user()->id;
                                                    $actualizarBeneficiario->save();

                                                    //PARA LA TABLA HISTORIAL 

                                                    $actualizacionHistorialBeneficiario = HistorialAsegurado::where('id_tipo_asegurado', $familiar['id_tipo_asegurado'])
                                                        ->where('id_corresponde', $actualizarBeneficiario->id)
                                                        ->where('id_persona', $familiar['id'])
                                                        ->first();

                                                    if ($actualizacionHistorialBeneficiario) {
                                                        $actualizacionHistorialBeneficiario->estado_vigencia = 'CESANTE';
                                                        $actualizacionHistorialBeneficiario->fecha_cesantia = $fechaBaja;
                                                        $actualizacionHistorialBeneficiario->fecha_baja = $fechaBajaDefinitiva;
                                                        $actualizacionHistorialBeneficiario->id_user_updated = auth()->user()->id;
                                                        $actualizacionHistorialBeneficiario->save();
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                DB::commit();
                            } else {
                                $bajaInstitucional = BajaInstitucional::create([
                                    'fecha_baja_inst' => $fechaBaja,
                                    'fecha_baja_ssu' => $fechaBajaDefinitiva,
                                    'id_persona' => $idPersona,
                                    'descripcion_motivo' => $motivoBaja . ' (BAJA POR PLANILLA CORRESPONDIENTE AL MES DE ' . $mes->mes . ' DE LA GESTIÓN ' . $gestion,
                                    'baja_definitiva' => false,
                                    'id_user_created' => auth()->user()->id,
                                ]);

                                //PARA LA TABLA HISTORIAL

                                if ($bajaInstitucional) {
                                    $actualizacionHistorial = HistorialAsegurado::where('id_tipo_asegurado', $persona->id_tipo_asegurado)
                                        ->where('id_corresponde', $titular->id)
                                        ->where('id_persona', $idPersona)
                                        ->first();

                                    if ($actualizacionHistorial) {
                                        $actualizacionHistorial->estado_vigencia = 'NO VIGENTE';
                                        $actualizacionHistorial->fecha_cesantia = $fechaBaja;
                                        $actualizacionHistorial->fecha_baja = $fechaBajaDefinitiva;
                                        $actualizacionHistorial->id_user_updated = auth()->user()->id;
                                        $actualizacionHistorial->save();
                                    }

                                    //PARA EL GRUPO FAMILIAR 

                                    $servicioGrupoFamiliar = new GrupoFamiliarPersona();

                                    $listadoFamiliares = $servicioGrupoFamiliar->encontrarPersona($idPersona);
                                    //dd($listadoFamiliares);

                                    if ($listadoFamiliares != []) {
                                        if (count($listadoFamiliares) > 1) {

                                            foreach ($listadoFamiliares as $familiar) {
                                                if ($familiar['id'] != $idPersona) {

                                                    $actualizarBeneficiario = Beneficiario::where('id_persona', $familiar['id'])
                                                        ->where('id_titular', $titular->id)
                                                        ->orderBy('id', 'desc')
                                                        ->first();

                                                    if ($actualizarBeneficiario) {

                                                        //PARA LA TABLA HISTORIAL 

                                                        $actualizacionHistorialBeneficiario = HistorialAsegurado::where('id_tipo_asegurado', $familiar['id_tipo_asegurado'])
                                                            ->where('id_corresponde', $actualizarBeneficiario->id)
                                                            ->where('id_persona', $familiar['id'])
                                                            ->first();

                                                        if ($actualizacionHistorialBeneficiario) {
                                                            $actualizacionHistorialBeneficiario->estado_vigencia = 'NO VIGENTE';
                                                            $actualizacionHistorialBeneficiario->fecha_cesantia = $fechaBaja;
                                                            $actualizacionHistorialBeneficiario->fecha_baja = $fechaBajaDefinitiva;
                                                            $actualizacionHistorialBeneficiario->id_user_updated = auth()->user()->id;
                                                            $actualizacionHistorialBeneficiario->save();
                                                        }

                                                        //MODIFICADO RECIEN EN LA TABLA BENEFICIARIO

                                                        $actualizarBeneficiario->vigencia = 'NO VIGENTE';
                                                        $actualizarBeneficiario->fecha_baja = $fechaBajaDefinitiva;
                                                        $actualizarBeneficiario->id_user_updated = auth()->user()->id;
                                                        $actualizarBeneficiario->save();

                                                        //PARA ACTUALIZAR A LA PERSONA

                                                        $actualizarPersonaBeneficiario = Persona::where('id', $familiar['id'])->first();

                                                        if ($actualizarPersonaBeneficiario) {
                                                            $actualizarPersonaBeneficiario->afiliado = false;
                                                            $actualizarPersonaBeneficiario->id_tipo_asegurado = 0;
                                                            $actualizarPersonaBeneficiario->id_user_updated = auth()->user()->id;
                                                            $actualizarPersonaBeneficiario->save();
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    $titular->estado_vigencia = 'NO VIGENTE';
                                    $titular->fecha_baja = $fechaBajaDefinitiva;
                                    $titular->id_user_updated = auth()->user()->id;
                                    $titular->save();

                                    //PARA ACTUALIZAR A LA PERSONA
                                    $actualizarPersona = Persona::where('id', $idPersona)->first();

                                    if ($actualizarPersona) {
                                        $actualizarPersona->afiliado = false;
                                        $actualizarPersona->id_tipo_asegurado = 0;
                                        $actualizarPersona->id_user_updated = auth()->user()->id;
                                        $actualizarPersona->save();
                                    }
                                }
                                DB::commit();
                            }
                        } else {
                            DB::commit();
                        }
                    }
                }
            } elseif (Str::contains($persona->tipoAseguradoPersona->tipo_asegurado, 'BENEFICIARIO')) {
                $beneficiario = Beneficiario::where('id_persona', $persona->id)->orderBy('id', 'desc')->first();

                if ($beneficiario) {
                    $institucionBeneficiario = BeneficiarioInstitucion::where('id_titular', $beneficiario->id)->where('id_institucion', $idInstitucion)->first();

                    if ($institucionBeneficiario) {
                        $institucionBeneficiario->estado = false;
                        $institucionBeneficiario->id_user_updated = auth()->user()->id;
                        $institucionBeneficiario->save();
                        DB::commit();
                    }
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }

    public function calcularCesantia($fechaBaja)
    {
        return Carbon::parse($fechaBaja)->addDays(60)->format('Y-m-d');
    }
}
