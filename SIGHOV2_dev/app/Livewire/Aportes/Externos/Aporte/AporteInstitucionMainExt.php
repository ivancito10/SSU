<?php

namespace App\Livewire\Aportes\Externos\Aporte;

use Livewire\Component;
use App\Models\Aportes\Comprobante;
use App\Models\Aportes\Institucion;
use App\Models\Aportes\FormaPago;
use App\Models\Aportes\ClaseAportante;
use App\Models\Aportes\Mes;
use App\Models\Aportes\Ufv;
use App\Models\Aportes\TasaInteres;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Estado;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\NumberToWords;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Aportes\AporteInstitucionalTemp;
use App\Models\Aportes\ComprobanteEliminado;

use Livewire\Attributes\On;

class AporteInstitucionMainExt extends Component
{
    public $compId;
    public $modal;
    public $comprobante;
    public $comprobanteId;
    public $modalPreliq;
    public $id_comp;
    public $id_institucion;
    public $nit;
    public $id_mes;
    public $gestion;
    public $tgn;
    public $beneficio_social;
    public $planilla_adicional;
    public $nombre_institucion;
    public $nombre_institucion_select;
    public $nombre_mes;
    public $m_tipo_clase;
    public $m_tasa_clase;
    public $m_fecha_pago; 


    // atributos FILA PRINCIPAL
    public $tipo_clase;
    public $nombre_clase;
    public $tasa_clase;
    public $salario_cotizable;
    public $importe_aporte_patronal = 0;


    //FILA ACTUALIZACION APORTE
    public $factor_activo = 1;
    public $actualizacion_aporte = 0;
    public $importe_actualizacion_aporte = 0;
    //FILA SubTotal (A)
    public $subtotal_liquidacion = 0;
    //FILA INTERESES
    public $tasa = 0;
    public $dias_mora = 0;

    //subtotal_liqudacion
    public $intereses = 0;
    //FILA MULTAS S/ INTERESES
    public $tasa_multas = 0.1;
    //
    //
    public $multas_sin_intereses = 0;
    //FILA MULTAS NO PLANILLA
    public $tasa_multa_no_planilla = 0.01;
    //
    //
    public $multas_no_planilla = 0;
    //FILA SubTotal (B)
    public $subtotal_recargos = 0;
    //FILA OTROS
    public $otros_suma = 0;
    //FILA SubTotal (C)
    public $descripcion = '';
    public $ministerio = 0;
    public $otros_resta = 0;
    public $subtotal_deducciones = 0;
    //FILA TOTAL(A+B+C)
    public $total_a_b_c = 0;

    //VARIABLES EXTRA E IMPORTANTES
    public $fecha_pago = null;
    public $fecha_inicio_plazo;
    public $fecha_fin_plazo;
    public $valor_ufv_plazo;
    public $valor_ufv_pago;
    public $monto_demasia = 0;
    public $monto_deduccion = 0;
    public $demasia_descripcion = "";
    public $deduccion_descripcion = "";

    public $presenta_planilla = true;
    public $numero_mes;
    public $mostrarContenido = false;
    public $total_comprobante = 0;
    public $monto_bajas_medicas = 0;
    public $observacion = " ";
    public $fecha_liquidacion_baja_medica = null;
    public $nro_registro = 0;
    public $m_nro_registro = "";
    public $anio_sistema = 0;
    public $anio_registrado = 0;
    public $forma_pago_c;
    public $diferencia_pago_liquidacion;
    public $numeroEnteroEnLetras;
    public $numeroDecimalEnLetras;
    public $parte_decimal;
    public $totalLiteral;

    //Variables para mostrar con decimales y miles
    public $m_total_salario_cotizable;
    public $m_importe_aporte_patronal;
    public $m_monto_demasia;
    public $m_actualizacion_aporte;
    public $m_importe_actualizacion_aporte;
    public $m_subtotal_liquidacion;
    public $m_intereses;
    public $m_multas_sin_intereses;
    public $m_multas_no_planilla;
    public $m_subtotal_recargos;
    public $m_monto_deduccion;
    public $m_ministerio;
    public $m_subtotal_deducciones;
    public $m_total_a_b_c;
    public $m_tasa;
    public $fecha_liquidado;
    public $fecha_liquidado_ant;
    public $reintegro_ant;
    public $planilla_adicional_ant;
    public $beneficio_social_ant;
    public $alerta_no_ufv;
    public $total_compensacion = 0;
    public $retroactivo;
    public $user_created_name, $user_updated_name, $fecha_user_created, $fecha_user_updated;

    public $nro_pago, $tipo_pago, $monto_excedente;

    public $nombres, $reintegro, $estado_comprobante,
        $total_salario_cotizable, $total_salario_cotizable_m, $aporte_a_liquidar, $importe;
     
    public $dato_mes, $dato_tipo_planilla;

    public function render()
    {
         

        $institucion = Institucion::all();
        $formapago = FormaPago::all();
        $mes = Mes::all();
        $clase = ClaseAportante::all();
        $comprobante = $this->mostrarComprobante($this->compId);
        return view('livewire.Aportes.Externos.aporte.aporte-institucion-main-ext', [
            'comprobante' => $comprobante,
            'institucion' => $institucion,
            'mes' => $mes,
            'clase' => $clase,
            'formapago' => $formapago,
        ]);
    }
     

    //mostrar un comprobante por su ID
    public function mostrarComprobante($compId)
    {
        if ($compId) {
            $comprobante = Comprobante::find($compId);
            $this->id_institucion = $comprobante->id_institucion;
            $this->id_mes = $comprobante->id_mes;
            $this->gestion = $comprobante->gestion;
            $this->salario_cotizable = $comprobante->total_salario_cotizable;

            $this->beneficio_social = $comprobante->beneficio_social;
            $this->planilla_adicional = $comprobante->planilla_adicional;
            $this->reintegro = $comprobante->reintegro;
            $this->retroactivo = $comprobante->retroactivo;
            
            $this->total_salario_cotizable = $comprobante->total_salario_cotizable;
            $this->total_salario_cotizable_m = number_format($comprobante->total_salario_cotizable, 2, ',', '.');
            $id_estado = $comprobante->id_estado;
            $this->total_compensacion = $comprobante->total_compensacion; 

            $this->monto_deduccion = $comprobante->monto_deduccion?? 0;
            $this->monto_excedente = $comprobante->monto_demasia ?? 0;

            $this->deduccion_descripcion = $comprobante->deduccion_descripcion ?? '-';
            $this->demasia_descripcion = $comprobante->demasia_descripcion ?? '-';
            $user_created = $comprobante->id_user_created;
            $this->fecha_user_created = $comprobante->created_at;

            $user_updated = $comprobante->id_user_updated;
            $this->fecha_user_updated = $comprobante->updated_at;


            $institucionActual = Institucion::find($comprobante->id_institucion);

            $this->nombre_institucion = $institucionActual->nombre;
            $this->nit = $institucionActual->nit;
            $this->tgn = $institucionActual->tgn;

            $mesActual = Mes::find($comprobante->id_mes);
            $this->nombre_mes = $mesActual->mes;
            $this->numero_mes = $mesActual->numero;

            $claseActual = ClaseAportante::find($institucionActual->id_clase_aportante);
            $this->nombre_clase = $claseActual->nombre;
            $this->tipo_clase = $claseActual->tipo;
            $this->tasa_clase = $claseActual->porcentaje;

            $this->importe = number_format(($comprobante->total_salario_cotizable * $claseActual->porcentaje) , 2, ',', '.');
            // monto a liquidar 
            $this->aporte_a_liquidar = number_format(($comprobante->importe_aporte_patronal) + ($comprobante->total_compensacion), 2, ',', '.');

            $estadoActual = Estado::find($id_estado);
            $this->estado_comprobante = $estadoActual->descripcion;

            $userCreated = User::find($user_created);
            $this->user_created_name = $userCreated->name;

            $userUpdated = User::find($user_updated);
            $this->user_updated_name = $userUpdated?->name?? '';

            $this->anio_sistema = Carbon::now()->year;

            $formaActual = FormaPago::where('id_comprobante', $compId)->first();
            if ($formaActual) {
                $this->forma_pago_c = $formaActual->total_forma_pago;
                $this->fecha_pago = Carbon::parse($formaActual->fecha_pago)->format('d/m/Y');
                $this->nro_pago = $formaActual->nro_pago;
                $this->tipo_pago = $formaActual->tipo_pago;
            } else {
                $this->forma_pago_c = 0;
            }

            return $comprobante;
        }
    }


    #[On('verFormaPago')]
    public function verFormaPago($data)
    {
        $this->mostrarComprobante($data);
        $this->mostrarFilePagoExt($data);
        $this->dispatch('modalVerFormaPago');
    }
    public function abrir_file_pago_ext(){

        $this->dispatch('modalFormaPagoExt');
        $this->skipRender();
    }

    public $pdf_forma_pago_ext;
    public function mostrarFilePagoExt($id_comp)
    {
        try {
            if($id_comp){
                $forma = FormaPago::where('id_comprobante',$id_comp)->first();
                if($forma != null){
                    $id_forma = $forma->id;
                    $path = 'Aportes/FormaPago/' . 'FP_' . $id_forma . '.pdf';
                    $fileContents = Storage::disk('ftp')->get($path);
                    $pdfBase64 = base64_encode($fileContents);
                    
                    $this->pdf_forma_pago_ext = $pdfBase64;
                }
            }
            
        } catch (\Throwable $th) {
            return null;
        }
    }


    #[On('ImprimirPDF')]
    public function GenerarPdf($data)
    {

        //codigo pdf
        $comprobante = Comprobante::join('aportes.institucion as i', 'comprobante.id_institucion', '=', 'i.id')
            ->join('administracion.zona as z', 'z.id', '=', 'i.id_zona')
            ->join('aportes.mes as m', 'm.id', '=', 'comprobante.id_mes')
            ->join('aportes.clase_aportante as ca', 'ca.id', '=', 'i.id_clase_aportante')
            ->join('aportes.forma_pago as fp', 'fp.id_comprobante', '=', 'comprobante.id')
            ->select(
                'comprobante.nro_registro_comprobante',
                'comprobante.gestion',
                'comprobante.fecha_liquidacion',
                'comprobante.cantidad_eventuales',
                'comprobante.cantidad_permanentes',
                'comprobante.tasa_interes',
                'comprobante.factor_actual',
                'comprobante.total_salario_cotizable',
                'comprobante.importe_intereses',
                'comprobante.dias_mora',
                'comprobante.subtotal_liquidacion',
                'comprobante.importe_multa_interes',
                'comprobante.importe_multa_no_planilla',
                'comprobante.monto_demasia',
                'comprobante.demasia_descripcion',
                'comprobante.monto_deduccion',
                'comprobante.deduccion_descripcion',
                'comprobante.monto_total_comprobante',
                'comprobante.monto_bajas_medicas',
                'comprobante.fecha_liquidacion_baja_medica',
                'comprobante.total_comprobante',
                'comprobante.id_aporte_v',
                'comprobante.reintegro',
                'comprobante.beneficio_social',
                'comprobante.reintegro',
                'comprobante.beneficio_social',
                'comprobante.importe_aporte_patronal',
                'comprobante.actualizacion_aporte',
                'comprobante.subtotal_recargos',
                'comprobante.subtotal_deducciones',
                'comprobante.ministerio',
                'comprobante.observacion',
                'comprobante.planilla',
                'comprobante.importe_actualizacion_aporte',
                'comprobante.id_user_created',
                'comprobante.id_user_updated',

                'i.nombre as nombre_institucion',
                'i.nit as nit',
                'i.nro_empleador as nro_empleador',
                'i.actividad_empleador as actividad_empleador',
                'i.direccion as direccion',
                'i.numero as numero_direccion',
                'i.telefono as telefono',

                'z.nombre as nombre_zona',

                'ca.nombre as nombre_clase_aportante',
                'ca.tipo as tipo_clase_aportante',
                'ca.porcentaje as porcentaje_clase_aportante',

                'm.mes as nombre_mes',

                'fp.nro_compro_anterior as nro_comp_anterior',
                'fp.fecha_pago as fecha_pago',
                'fp.tipo_pago as tipo_pago',
                'fp.nro_pago as nro_pago',
                'fp.total_forma_pago as total_forma_pago',
                'fp.total_compro_anterior as total_compro_anterior'

            )
            ->where('comprobante.id', $data['comprobanteId']) // Filtra por rowId
            ->first();
        if ($comprobante) {
            $comprobante->nombre_institucion = utf8_encode($comprobante->nombre_institucion);
            $comprobante->nombre_zona = utf8_encode($comprobante->nombre_zona);
            $comprobante->nombre_clase_aportante = utf8_encode($comprobante->nombre_clase_aportante);
            $comprobante->nombre_mes = utf8_encode($comprobante->nombre_mes);

            // Convertir la fecha a un formato legible
            $comprobante->fecha_liquidacion = Carbon::parse($comprobante->fecha_liquidacion)->translatedFormat('d \d\e F \d\e Y');
        }
        $numero = $comprobante->total_forma_pago;
        $numero_formateado = number_format($numero, 2, '.', '');
        $partes = explode('.', $numero_formateado);
        $parte_decimal = isset($partes[1]) ? $partes[1] : '';
        $numeroEnteroEnLetras = NumberToWords::convertir(floor($numero));
        $numeroDecimalEnLetras = NumberToWords::convertir($parte_decimal);


        $fechaActual = Carbon::now()->format('d/m/Y');

        $pdf = PDF::loadView('Livewire.Aportes.aportes-institucionales.prueba', compact('comprobante', 'numeroEnteroEnLetras', 'numeroDecimalEnLetras', 'parte_decimal'))
            ->setPaper('A4', 'portrait');
        return $pdf->download('document.pdf');
    }

    #[On('aportantesGrupoExt')]
    public function mostrarAportantes($data)
    {
        $this->compId = $data['comprobanteId'];
        $this->modalAportantesExt = $data['modalAportantesExt'];
        $this->dispatch('openModalAportantesExt');
    }
    #[On('aportantesGrupoTempExt')]
    public function mostrarAportantesTemp($data)
    {
        $this->compId = $data['comprobanteId'];
        $this->modalAportantesTempExt = $data['modalAportantesTempExt'];
        $this->dispatch('openModalAportantesTempExt');
    }

    #[On('pdfCopiaComp')]
    public function pdfCopiaComp($data)
    {
        $this->mostrarArchivoPdf($data);
        //$this->dispatch('modalVerPdfCopia');

    }
    public $pdf_vista;
    public function mostrarArchivoPdf($id)
    {
        try {
            $this->js('window.open("/aportes/pdf/' . $id . '", "_blank")'); 
        } catch (\Throwable $th) {
            return null;
        }
    }

    #[On('pdfPlanilla')]
    public function pdfPlanilla($data)
    {
        $this->mostrarPlanillaPdf($data);
    }
    public function mostrarPlanillaPdf($id)
    {
        try {
            $this->js('window.open("aportes/pdfplanilla/' . $id . '", "_blank")');
        } catch (\Throwable $th) {
            return null;
        }
    }

    #[On('funcionEliminarPlanilla')]
    public function funcionEliminarPlanilla($data){
        $this->id_comp = $data;
        $comprobante = Comprobante::where('id',$this->id_comp)->first();
        $id_mes = $comprobante->id_mes; 

        $mes = Mes::where('id', $id_mes)->first();
        $this->dato_mes = $mes->mes;

        $this->dato_tipo_planilla = match (true) {
            $comprobante->reintegro => 'REINTEGRO',
            $comprobante->beneficio_social => 'BENEFICIO SOCIAL',
            $comprobante->planilla_adicional => 'PLANILLA ADICIONAL',
            $comprobante->retroactivo => 'RETROACTIVO',
            default => 'MENSUAL', // valor por defecto si ninguno es true
        };
 
        $this->dispatch('confirmarEliminarPlanilla', dato_mes: $this->dato_mes, dato_tipo_planilla: $this->dato_tipo_planilla);

    }

    public $tipo_planilla;
    #[On('eliminarPlanillaGuardar')]
    public function eliminarPlanillaGuardar($motivo)
    {  
        DB::beginTransaction();
        try { 
            $eliminar_com = Comprobante::where('id',$this->id_comp)->first();

            $id_institucion = $eliminar_com->id_institucion;
            $estado_umsa = $eliminar_com->estado_umsa; 
            $id_mes = $eliminar_com->id_mes;
            $gestion = $eliminar_com->gestion;
            $total_salario_cotizable = $eliminar_com->total_salario_cotizable;
            $nro_aportantes = intval(($eliminar_com->cantidad_eventuales ?? 0) + ($eliminar_com->cantidad_permanentes ?? 0));
 
            $reintegro = $eliminar_com->reintegro;
            $beneficio_social = $eliminar_com->beneficio_social;
            $planilla_adicional = $eliminar_com->planilla_adicional;
            $retroactivo = $eliminar_com->retroactivo;


            if($reintegro == false && $beneficio_social == false && $planilla_adicional == false  && $retroactivo == false){
                $this->tipo_planilla = 0;
            }elseif($reintegro == true){
                $this->tipo_planilla = 1;
            }elseif($beneficio_social == true){
                $this->tipo_planilla = 2;
            }elseif($planilla_adicional == true){
                $this->tipo_planilla = 3;
            }elseif($retroactivo == true){
                $this->tipo_planilla = 4;
            }
   
            if($this->tipo_planilla == 0 || $this->tipo_planilla ==  4 ){

                //  UMSA
                if($id_institucion == 114){ 

                    // Aqui empieza la eliminacion de planillas por UMSA 
                    if($estado_umsa == true){  

                        $comp_doc = Comprobante::where('id_institucion', 2)
                        ->where('id_mes',  $eliminar_com->id_mes)
                        ->where('gestion',  $eliminar_com->gestion)
                        ->where('reintegro',  $eliminar_com->reintegro)
                        ->where('beneficio_social',  $eliminar_com->beneficio_social)
                        ->where('planilla_adicional',  $eliminar_com->planilla_adicional)
                        ->where('retroactivo',  $eliminar_com->retroactivo)
                        ->first();
        
                        $comp_adm = Comprobante::where('id_institucion', 6)
                        ->where('id_mes',  $eliminar_com->id_mes)
                        ->where('gestion',  $eliminar_com->gestion)
                        ->where('reintegro',  $eliminar_com->reintegro)
                        ->where('beneficio_social',  $eliminar_com->beneficio_social)
                        ->where('planilla_adicional',  $eliminar_com->planilla_adicional)
                        ->where('retroactivo',  $eliminar_com->retroactivo)
                        ->first();  

                        //Eliminar los 3 comprobantes 
                        // Eliminar a los docentes
                        $id_comp_doc = $comp_doc->id;
                        $id_mes_doc = $comp_doc->id_mes;
                        $gestion_doc = $comp_doc->gestion;
                        $total_salario_cotizable_doc = $comp_doc->total_salario_cotizable;
                        $nro_aportantes_doc = intval(($comp_doc->cantidad_eventuales ?? 0) + ($comp_doc->cantidad_permanentes ?? 0));
        
                        $eliminarDoc = AporteInstitucionalTemp::where('id_comp', $id_comp_doc) 
                            ->where('umsa_doc', '=', true)
                            ->delete();

                        $comp_doc->delete(); 
                        $this->crear_planilla_eliminada($id_comp_doc, 2, $id_mes_doc, $gestion_doc, $total_salario_cotizable_doc,
                        $nro_aportantes_doc, $motivo);

                        // Eliminar administrativos
                        $id_comp_adm = $comp_adm->id;
                        $id_mes_adm = $comp_adm->id_mes;
                        $gestion_adm = $comp_adm->gestion;
                        $total_salario_cotizable_adm = $comp_adm->total_salario_cotizable;
                        $nro_aportantes_adm = intval(($comp_adm->cantidad_eventuales ?? 0) + ($comp_adm->cantidad_permanentes ?? 0));
        
                        $eliminarAdmin = AporteInstitucionalTemp::where('id_comp', $id_comp_adm) 
                            ->where('umsa_adm', '=', true)
                            ->delete();

                        $comp_adm->delete(); 
                        $this->crear_planilla_eliminada($id_comp_adm, 6, $id_mes_adm, $gestion_adm, $total_salario_cotizable_adm,
                        $nro_aportantes_adm, $motivo);


                        // Eliminar umsa general 
                        $id_comp_umsa = $eliminar_com->id;
                        $id_mes_umsa = $eliminar_com->id_mes;
                        $gestion_umsa = $eliminar_com->gestion;
                        $total_salario_cotizable_umsa = $eliminar_com->total_salario_cotizable;
                        $nro_aportantes_umsa = intval(($eliminar_com->cantidad_eventuales ?? 0) + ($eliminar_com->cantidad_permanentes ?? 0));

                        $eliminarAporteUmsa = AporteInstitucionalTemp::where('id_comp', $id_comp_umsa) 
                            ->delete();

                        $eliminar_com->delete();  
                        $this->crear_planilla_eliminada($id_comp_umsa, 114, $id_mes_umsa, $gestion_umsa, $total_salario_cotizable_umsa,
                        $nro_aportantes_umsa, $motivo);

                        DB::commit();

                        // Guardado
                        $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionalesExterno');
                        $this->dispatch('PlanillaEliminada'); 
                         
                    }elseif($estado_umsa == false){
                        
                        $comp_doc = Comprobante::where('id_institucion', 2)
                            ->where('id_mes',  $eliminar_com->id_mes)
                            ->where('gestion',  $eliminar_com->gestion)
                            ->where('reintegro',  $eliminar_com->reintegro)
                            ->where('beneficio_social',  $eliminar_com->beneficio_social)
                            ->where('planilla_adicional',  $eliminar_com->planilla_adicional)
                            ->where('retroactivo',  $eliminar_com->retroactivo)
                            ->first();
        
                        $comp_adm = Comprobante::where('id_institucion', 6)
                            ->where('id_mes',  $eliminar_com->id_mes)
                            ->where('gestion',  $eliminar_com->gestion)
                            ->where('reintegro',  $eliminar_com->reintegro)
                            ->where('beneficio_social',  $eliminar_com->beneficio_social)
                            ->where('planilla_adicional',  $eliminar_com->planilla_adicional)
                            ->where('retroactivo',  $eliminar_com->retroactivo)
                            ->first();

                        if($comp_doc !== null){

                            // Eliminar a los docentes
                            $id_comp_doc = $comp_doc->id;
                            $id_mes_doc = $comp_doc->id_mes;
                            $gestion_doc = $comp_doc->gestion;
                            $total_salario_cotizable_doc = $comp_doc->total_salario_cotizable;
                            $nro_aportantes_doc = intval(($comp_doc->cantidad_eventuales ?? 0) + ($comp_doc->cantidad_permanentes ?? 0));
            
                            $eliminarDoc = AporteInstitucionalTemp::where('id_comp', $id_comp_doc) 
                                ->where('umsa_doc', '=', true)
                                ->delete();

                            $comp_doc->delete(); 
                            $this->crear_planilla_eliminada($id_comp_doc, 2, $id_mes_doc, $gestion_doc, $total_salario_cotizable_doc,
                            $nro_aportantes_doc, $motivo);
    
    
                            // Eliminar umsa general
                            
                            $id_comp_umsa = $eliminar_com->id;
                            $id_mes_umsa = $eliminar_com->id_mes;
                            $gestion_umsa = $eliminar_com->gestion;
                            $total_salario_cotizable_umsa = $eliminar_com->total_salario_cotizable;
                            $nro_aportantes_umsa = intval(($eliminar_com->cantidad_eventuales ?? 0) + ($eliminar_com->cantidad_permanentes ?? 0));
    
                            $eliminarAporteUmsa = AporteInstitucionalTemp::where('id_comp', $id_comp_umsa) 
                                ->delete();
    
                            $eliminar_com->delete();  
                            $this->crear_planilla_eliminada($id_comp_umsa, 114, $id_mes_umsa, $gestion_umsa, $total_salario_cotizable_umsa,
                            $nro_aportantes_umsa, $motivo);
    
                            DB::commit();
    
                            // Guardado
                            $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionalesExterno');
                            $this->dispatch('PlanillaEliminada'); 
                            


                        }elseif($comp_adm !== null){ 
                            // Eliminar administrativos
                            $id_comp_adm = $comp_adm->id;
                            $id_mes_adm = $comp_adm->id_mes;
                            $gestion_adm = $comp_adm->gestion;
                            $total_salario_cotizable_adm = $comp_adm->total_salario_cotizable;
                            $nro_aportantes_adm = intval(($comp_adm->cantidad_eventuales ?? 0) + ($comp_adm->cantidad_permanentes ?? 0));
            
                            $eliminarAdmin = AporteInstitucionalTemp::where('id_comp', $id_comp_adm) 
                                ->where('umsa_adm', '=', true)
                                ->delete();

                            $comp_adm->delete(); 
                            $this->crear_planilla_eliminada($id_comp_adm, 6, $id_mes_adm, $gestion_adm, $total_salario_cotizable_adm,
                            $nro_aportantes_adm, $motivo);


                            // Eliminar umsa general
                            
                            $id_comp_umsa = $eliminar_com->id;
                            $id_mes_umsa = $eliminar_com->id_mes;
                            $gestion_umsa = $eliminar_com->gestion;
                            $total_salario_cotizable_umsa = $eliminar_com->total_salario_cotizable;
                            $nro_aportantes_umsa = intval(($eliminar_com->cantidad_eventuales ?? 0) + ($eliminar_com->cantidad_permanentes ?? 0));

                            

                            $eliminarAporteUmsa = AporteInstitucionalTemp::where('id_comp', $id_comp_umsa) 
                                ->delete();

                            $eliminar_com->delete();  
                            $this->crear_planilla_eliminada($id_comp_umsa, 114, $id_mes_umsa, $gestion_umsa, $total_salario_cotizable_umsa,
                            $nro_aportantes_umsa, $motivo);

                            DB::commit();

                            // Guardado
                            $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionalesExterno'); 
                            $this->dispatch('PlanillaEliminada'); 
                            
                            
                        }
                    }elseif($estado_umsa == null){
                        // Eliminar umsa general 
                        $id_comp_umsa = $eliminar_com->id;
                        $id_mes_umsa = $eliminar_com->id_mes;
                        $gestion_umsa = $eliminar_com->gestion;
                        $total_salario_cotizable_umsa = $eliminar_com->total_salario_cotizable;
                        $nro_aportantes_umsa = intval(($eliminar_com->cantidad_eventuales ?? 0) + ($eliminar_com->cantidad_permanentes ?? 0));

                        $eliminarAporteUmsa = AporteInstitucionalTemp::where('id_comp', $id_comp_umsa) 
                            ->delete();

                        $eliminar_com->delete();  
                        $this->crear_planilla_eliminada($id_comp_umsa, 114, $id_mes_umsa, $gestion_umsa, $total_salario_cotizable_umsa,
                        $nro_aportantes_umsa, $motivo);

                        DB::commit();

                        // Guardado
                        $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionalesExterno');
                        $this->dispatch('PlanillaEliminada'); 

                    }

                }elseif($id_institucion == 2){
                    
                    $eliminar_comp_id = $eliminar_com->id;

                    $comp_umsa = Comprobante::where('id_institucion', 114)
                    ->where('id_mes',  $eliminar_com->id_mes)
                    ->where('gestion',  $eliminar_com->gestion)
                    ->where('reintegro',  $eliminar_com->reintegro)
                    ->where('beneficio_social',  $eliminar_com->beneficio_social)
                    ->where('planilla_adicional',  $eliminar_com->planilla_adicional)
                    ->where('retroactivo',  $eliminar_com->retroactivo)
                    ->first();
    

                    if($comp_umsa){  
                        $v_estado_umsa = $comp_umsa->estado_umsa; 
                        if($v_estado_umsa == true){ 
                            // Actualizar el estado a FALSE de Comp UMSA y debe eliminarse  Doc 
                                $eventuales= $eliminar_com->cantidad_eventuales;
                                $permanentes= $eliminar_com->cantidad_permanentes;
                                $total_salario_cotizable= $eliminar_com->total_salario_cotizable;
                                $importe= $eliminar_com->importe_aporte_patronal;
                                $total_compensacion= $eliminar_com->total_compensacion;

                                // Aqui obtenemos el listado de todas las personas de planilla docentes
                                $arrayIdPersonas = AporteInstitucionalTemp::where('id_institucion', 2)
                                ->where('gestion', $eliminar_com->gestion)
                                ->where('id_mes', $eliminar_com->id_mes)
                                ->where('reintegro_incr_salarial', '=', $eliminar_com->reintegro)
                                ->where('beneficio_social', '=', $eliminar_com->beneficio_social)
                                ->where('planilla_adicional', '=', $eliminar_com->planilla_adicional)
                                ->where('retroactivo', '=', $eliminar_com->retroactivo)
                                ->where('umsa_doc', '=', true)
                                ->pluck('id_persona')
                                ->toArray();

                                // Aqui actualizamos el estado y datos principales para umsa
        
                                $eventuales_v = $comp_umsa->cantidad_eventuales- $eventuales;
                                $permanentes_v = $comp_umsa->cantidad_permanentes - $permanentes;
                                $total_salario_cotizable_v =  $comp_umsa->total_salario_cotizable - $total_salario_cotizable;
                                $importe_v =  $comp_umsa->importe_aporte_patronal - $importe;
                                $total_compensacion_v =  $comp_umsa->total_compensacion - $total_compensacion;
                        
                                if ($comp_umsa) {
                                    $comp_umsa->cantidad_eventuales =  $eventuales_v;
                                    $comp_umsa->cantidad_permanentes = $permanentes_v;
                                    $comp_umsa->total_salario_cotizable = $total_salario_cotizable_v;
                                    $comp_umsa->importe_aporte_patronal = $importe_v;
                                    $comp_umsa->total_compensacion = $total_compensacion_v;
                                    $comp_umsa->estado_umsa = false;
                                    $comp_umsa->id_user_updated = auth()->user()->id;
                                    $comp_umsa->save();
                                }

                                // Aqui eliminamos el array de personas que estaban en la planilla umsa como docentes

                                $eliminarDocdeUmsa = AporteInstitucionalTemp::where('id_institucion', 114)
                                    ->where('gestion', $eliminar_com->gestion)
                                    ->where('id_mes', $eliminar_com->id_mes)
                                    ->where('reintegro_incr_salarial', '=', $eliminar_com->reintegro)
                                    ->where('beneficio_social', '=', $eliminar_com->beneficio_social)
                                    ->where('planilla_adicional', '=', $eliminar_com->planilla_adicional)
                                    ->where('retroactivo', '=', $eliminar_com->retroactivo)
                                    ->where('umsa_doc', '=', true)
                                    ->whereIn('id_persona', $arrayIdPersonas)
                                    ->delete();

                                // Aqui ya eliminamos la planilla de docentes  
                                // Eliminar a los docentes
                                $id_comp_doc = $eliminar_com->id;
                                $id_mes_doc = $eliminar_com->id_mes;
                                $gestion_doc = $eliminar_com->gestion;
                                $total_salario_cotizable_doc = $eliminar_com->total_salario_cotizable;
                                $nro_aportantes_doc = intval(($eliminar_com->cantidad_eventuales ?? 0) + ($eliminar_com->cantidad_permanentes ?? 0));
                
                                $eliminarDoc = AporteInstitucionalTemp::where('id_institucion', 2)
                                    ->where('gestion', $eliminar_com->gestion)
                                    ->where('id_mes', $eliminar_com->id_mes)
                                    ->where('reintegro_incr_salarial', '=', $eliminar_com->reintegro)
                                    ->where('beneficio_social', '=', $eliminar_com->beneficio_social)
                                    ->where('planilla_adicional', '=', $eliminar_com->planilla_adicional)
                                    ->where('retroactivo', '=', $eliminar_com->retroactivo)
                                    ->where('umsa_doc', '=', true)
                                    ->delete();

                                $eliminar_com->delete(); 
                                $this->crear_planilla_eliminada($eliminar_comp_id, 2, $id_mes_doc, $gestion_doc, $total_salario_cotizable_doc,
                                $nro_aportantes_doc, $motivo);

                                DB::commit();
            
                                // Guardado
                                $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionalesExterno'); 
                                $this->dispatch('PlanillaEliminada'); 
                                
    

                        }elseif($v_estado_umsa == false){
                            // Eliminamos planilla docentes y planilla umsa
                            
                            // Eliminar a los docentes
                            $id_comp_doc = $eliminar_com->id;
                            $id_mes_doc = $eliminar_com->id_mes;
                            $gestion_doc = $eliminar_com->gestion;
                            $total_salario_cotizable_doc = $eliminar_com->total_salario_cotizable;
                            $nro_aportantes_doc = intval(($eliminar_com->cantidad_eventuales ?? 0) + ($eliminar_com->cantidad_permanentes ?? 0));
            
                            $eliminarDoc = AporteInstitucionalTemp::where('id_institucion', 2)
                                ->where('gestion', $eliminar_com->gestion)
                                ->where('id_mes', $eliminar_com->id_mes)
                                ->where('reintegro_incr_salarial', '=', $eliminar_com->reintegro)
                                ->where('beneficio_social', '=', $eliminar_com->beneficio_social)
                                ->where('planilla_adicional', '=', $eliminar_com->planilla_adicional)
                                ->where('retroactivo', '=', $eliminar_com->retroactivo)
                                ->where('umsa_doc', '=', true)
                                ->delete();

                            $eliminar_com->delete(); 
                            $this->crear_planilla_eliminada($id_comp_doc, 2, $id_mes_doc, $gestion_doc, $total_salario_cotizable_doc,
                            $nro_aportantes_doc, $motivo);
    
    
                            // Eliminar umsa general 
                            $id_comp_umsa = $comp_umsa->id;
                            $id_mes_umsa = $comp_umsa->id_mes;
                            $gestion_umsa = $comp_umsa->gestion;
                            $total_salario_cotizable_umsa = $comp_umsa->total_salario_cotizable;
                            $nro_aportantes_umsa = intval(($comp_umsa->cantidad_eventuales ?? 0) + ($comp_umsa->cantidad_permanentes ?? 0));
    
                            $eliminarAporteUmsa = AporteInstitucionalTemp::where('id_institucion', 114)
                                ->where('gestion', $comp_umsa->gestion)
                                ->where('id_mes', $comp_umsa->id_mes)
                                ->where('reintegro_incr_salarial', '=', $comp_umsa->reintegro)
                                ->where('beneficio_social', '=', $comp_umsa->beneficio_social)
                                ->where('planilla_adicional', '=', $comp_umsa->planilla_adicional)
                                ->where('retroactivo', '=', $comp_umsa->retroactivo)
                                ->delete();
    
                            $comp_umsa->delete();

                            $this->crear_planilla_eliminada($id_comp_umsa, 114, $id_mes_umsa, $gestion_umsa, $total_salario_cotizable_umsa,
                            $nro_aportantes_umsa, $motivo);
    
                            DB::commit();
    
                            // Guardado
                            $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionalesExterno'); 
                            $this->dispatch('PlanillaEliminada');
                            

                        }
                    }else{
                            
                        // Eliminar solo a la planilla de los docentes
                        $id_comp_doc = $eliminar_com->id;
                        $id_mes_doc = $eliminar_com->id_mes;
                        $gestion_doc = $eliminar_com->gestion;
                        $total_salario_cotizable_doc = $eliminar_com->total_salario_cotizable;
                        $nro_aportantes_doc = intval(($eliminar_com->cantidad_eventuales ?? 0) + ($eliminar_com->cantidad_permanentes ?? 0));
        
                        $eliminarDoc = AporteInstitucionalTemp::where('id_comp', $eliminar_com->id) 
                            ->where('umsa_doc', '=', true)
                            ->delete();

                        $eliminar_com->delete(); 
                        
                        $this->crear_planilla_eliminada($id_comp_doc, 2, $id_mes_doc, $gestion_doc, $total_salario_cotizable_doc,
                        $nro_aportantes_doc, $motivo);

                        DB::commit();

                        // Guardado
                        $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionalesExterno'); 
                        $this->dispatch('PlanillaEliminada'); 
                        

                    }

                }elseif($id_institucion == 6){
                 

                    $eliminar_comp_id = $eliminar_com->id;

                    $comp_umsa = Comprobante::where('id_institucion', 114)
                    ->where('id_mes',  $eliminar_com->id_mes)
                    ->where('gestion',  $eliminar_com->gestion)
                    ->where('reintegro',  $eliminar_com->reintegro)
                    ->where('beneficio_social',  $eliminar_com->beneficio_social)
                    ->where('planilla_adicional',  $eliminar_com->planilla_adicional)
                    ->where('retroactivo',  $eliminar_com->retroactivo)
                    ->first();
    

                    if($comp_umsa){  
                        $v_estado_umsa = $comp_umsa->estado_umsa; 
                        if($v_estado_umsa == true){ 
                            // Actualizar el estado a FALSE de Comp UMSA, para cualquier caso que se elimine; Adm  
                                $eventuales= $eliminar_com->cantidad_eventuales;
                                $permanentes= $eliminar_com->cantidad_permanentes;
                                $total_salario_cotizable= $eliminar_com->total_salario_cotizable;
                                $importe= $eliminar_com->importe_aporte_patronal;
                                $total_compensacion= $eliminar_com->total_compensacion;

                                // Aqui obtenemos el listado de todas las personas de planilla administrativos
                                $arrayIdPersonas = AporteInstitucionalTemp::where('id_institucion', 6)
                                ->where('gestion', $eliminar_com->gestion)
                                ->where('id_mes', $eliminar_com->id_mes)
                                ->where('reintegro_incr_salarial', '=', $eliminar_com->reintegro)
                                ->where('beneficio_social', '=', $eliminar_com->beneficio_social)
                                ->where('planilla_adicional', '=', $eliminar_com->planilla_adicional)
                                ->where('retroactivo', '=', $eliminar_com->retroactivo)
                                ->where('umsa_adm', '=', true)
                                ->pluck('id_persona')
                                ->toArray();

                                // Aqui actualizamos el estado y datos principales para umsa
        
                                $eventuales_v = $comp_umsa->cantidad_eventuales- $eventuales;
                                $permanentes_v = $comp_umsa->cantidad_permanentes - $permanentes;
                                $total_salario_cotizable_v =  $comp_umsa->total_salario_cotizable - $total_salario_cotizable;
                                $importe_v =  $comp_umsa->importe_aporte_patronal - $importe;
                                $total_compensacion_v =  $comp_umsa->total_compensacion - $total_compensacion;
                        
                                if ($comp_umsa) {
                                    $comp_umsa->cantidad_eventuales =  $eventuales_v;
                                    $comp_umsa->cantidad_permanentes = $permanentes_v;
                                    $comp_umsa->total_salario_cotizable = $total_salario_cotizable_v;
                                    $comp_umsa->importe_aporte_patronal = $importe_v;
                                    $comp_umsa->total_compensacion = $total_compensacion_v;
                                    $comp_umsa->estado_umsa = false;
                                    $comp_umsa->id_user_updated = auth()->user()->id;
                                    $comp_umsa->save();
                                }

                                // Aqui eliminamos el array de personas que estaban en la planilla umsa como administrativos

                                $eliminarAdmdeUmsa = AporteInstitucionalTemp::where('id_institucion', 114)
                                    ->where('gestion', $eliminar_com->gestion)
                                    ->where('id_mes', $eliminar_com->id_mes)
                                    ->where('reintegro_incr_salarial', '=', $eliminar_com->reintegro)
                                    ->where('beneficio_social', '=', $eliminar_com->beneficio_social)
                                    ->where('planilla_adicional', '=', $eliminar_com->planilla_adicional)
                                    ->where('retroactivo', '=', $eliminar_com->retroactivo)
                                    ->where('umsa_adm', '=', true)
                                    ->whereIn('id_persona', $arrayIdPersonas)
                                    ->delete();

                                // Aqui ya eliminamos la planilla de administrativos  
                                // Eliminar a los administrativos
                                $id_comp_adm = $eliminar_com->id;
                                $id_mes_adm = $eliminar_com->id_mes;
                                $gestion_adm = $eliminar_com->gestion;
                                $total_salario_cotizable_adm = $eliminar_com->total_salario_cotizable;
                                $nro_aportantes_adm = intval(($eliminar_com->cantidad_eventuales ?? 0) + ($eliminar_com->cantidad_permanentes ?? 0));
                
                                $eliminarAdm = AporteInstitucionalTemp::where('id_institucion', 6)
                                    ->where('gestion', $eliminar_com->gestion)
                                    ->where('id_mes', $eliminar_com->id_mes)
                                    ->where('reintegro_incr_salarial', '=', $eliminar_com->reintegro)
                                    ->where('beneficio_social', '=', $eliminar_com->beneficio_social)
                                    ->where('planilla_adicional', '=', $eliminar_com->planilla_adicional)
                                    ->where('retroactivo', '=', $eliminar_com->retroactivo)
                                    ->where('umsa_adm', '=', true)
                                    ->delete();

                                $eliminar_com->delete(); 

                                $this->crear_planilla_eliminada($eliminar_comp_id, 6, $id_mes_adm, $gestion_adm, $total_salario_cotizable_adm,
                                $nro_aportantes_adm, $motivo);

                                DB::commit();
            
                                // Guardado
                                $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionalesExterno'); 
                                $this->dispatch('PlanillaEliminada');  

                        }elseif($v_estado_umsa == false){
                            // Eliminamos planilla administrativos y planilla umsa
                            
                            // Eliminar a los administrativos
                            $id_comp_adm = $eliminar_com->id;
                            $id_mes_adm = $eliminar_com->id_mes;
                            $gestion_adm = $eliminar_com->gestion;
                            $total_salario_cotizable_adm = $eliminar_com->total_salario_cotizable;
                            $nro_aportantes_adm = intval(($eliminar_com->cantidad_eventuales ?? 0) + ($eliminar_com->cantidad_permanentes ?? 0));
            
                            $eliminarAdm = AporteInstitucionalTemp::where('id_institucion', 6)
                                ->where('gestion', $eliminar_com->gestion)
                                ->where('id_mes', $eliminar_com->id_mes)
                                ->where('reintegro_incr_salarial', '=', $eliminar_com->reintegro)
                                ->where('beneficio_social', '=', $eliminar_com->beneficio_social)
                                ->where('planilla_adicional', '=', $eliminar_com->planilla_adicional)
                                ->where('retroactivo', '=', $eliminar_com->retroactivo)
                                ->where('umsa_adm', '=', true)
                                ->delete();

                            $eliminar_com->delete(); 
                            $this->crear_planilla_eliminada($id_comp_adm, 6, $id_mes_adm, $gestion_adm, $total_salario_cotizable_adm,
                            $nro_aportantes_adm, $motivo);
    
    
                            // Eliminar umsa general 
                            $id_comp_umsa = $comp_umsa->id;
                            $id_mes_umsa = $comp_umsa->id_mes;
                            $gestion_umsa = $comp_umsa->gestion;
                            $total_salario_cotizable_umsa = $comp_umsa->total_salario_cotizable;
                            $nro_aportantes_umsa = intval(($comp_umsa->cantidad_eventuales ?? 0) + ($comp_umsa->cantidad_permanentes ?? 0));
    
                            $eliminarAporteUmsa = AporteInstitucionalTemp::where('id_institucion', 114)
                                ->where('gestion', $comp_umsa->gestion)
                                ->where('id_mes', $comp_umsa->id_mes)
                                ->where('reintegro_incr_salarial', '=', $comp_umsa->reintegro)
                                ->where('beneficio_social', '=', $comp_umsa->beneficio_social)
                                ->where('planilla_adicional', '=', $comp_umsa->planilla_adicional)
                                ->where('retroactivo', '=', $comp_umsa->retroactivo)
                                ->delete();
    
                            $comp_umsa->delete();

                            $this->crear_planilla_eliminada($id_comp_umsa, 114, $id_mes_umsa, $gestion_umsa, $total_salario_cotizable_umsa,
                            $nro_aportantes_umsa, $motivo);
    
                            DB::commit();
    
                            // Guardado
                            $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionalesExterno'); 
                            $this->dispatch('PlanillaEliminada'); 
                             
                        }
                    }else{
                            
                        // Eliminar solo a la planilla de los administrativos
                        $id_comp_adm = $eliminar_com->id;
                        $id_mes_adm = $eliminar_com->id_mes;
                        $gestion_adm = $eliminar_com->gestion;
                        $total_salario_cotizable_adm = $eliminar_com->total_salario_cotizable;
                        $nro_aportantes_adm = intval(($eliminar_com->cantidad_eventuales ?? 0) + ($eliminar_com->cantidad_permanentes ?? 0));
        
                        $eliminarAdm = AporteInstitucionalTemp::where('id_comp', $eliminar_com->id) 
                            ->where('umsa_adm', '=', true)
                            ->delete();

                        $eliminar_com->delete(); 
                        
                        $this->crear_planilla_eliminada($id_comp_adm, 6, $id_mes_adm, $gestion_adm, $total_salario_cotizable_adm,
                        $nro_aportantes_adm, $motivo);

                        DB::commit();

                        // Guardado
                        $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionalesExterno'); 
                        $this->dispatch('PlanillaEliminada'); 
                    } 

                }else{ 
                    
                    // Aqui eliminamos planillas diferentes a UMSA - ADM UMSA - DOC UMSA
                    $id_comp_x = $eliminar_com->id;
                    $id_insti_x = $eliminar_com->id_institucion;
                    $id_mes_x = $eliminar_com->id_mes;
                    $gestion_x = $eliminar_com->gestion;
                    $total_salario_cotizable_x = $eliminar_com->total_salario_cotizable;
                    $nro_aportantes_x = intval(($eliminar_com->cantidad_eventuales ?? 0) + ($eliminar_com->cantidad_permanentes ?? 0));
    
                    $eliminarAporteTempX = AporteInstitucionalTemp::where('id_comp', $id_comp_x)
                    ->delete();

                    $eliminar_com->delete(); 
                    
                    $this->crear_planilla_eliminada($id_comp_x, $id_insti_x, $id_mes_x, $gestion_x, $total_salario_cotizable_x,
                    $nro_aportantes_x, $motivo);
                    
                    DB::commit();
                    
                    // Guardado
                    $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionalesExterno');
                    $this->dispatch('PlanillaEliminada'); 
                    

                }
 
            }else{
                // Aqui eliminamos planillas diferentes a UMSA - ADM UMSA - DOC UMSA
                    $id_comp_x = $eliminar_com->id;
                    $id_insti_x = $eliminar_com->id_institucion;
                    $id_mes_x = $eliminar_com->id_mes;
                    $gestion_x = $eliminar_com->gestion;
                    $total_salario_cotizable_x = $eliminar_com->total_salario_cotizable;
                    $nro_aportantes_x = intval(($eliminar_com->cantidad_eventuales ?? 0) + ($eliminar_com->cantidad_permanentes ?? 0));
    
                    $eliminarAporteTempX = AporteInstitucionalTemp::where('id_comp', $this->id_comp)
                    ->delete();

                    $eliminar_com->delete(); 
                    
                    $this->crear_planilla_eliminada($id_comp_x, $id_insti_x, $id_mes_x, $gestion_x, $total_salario_cotizable_x,
                    $nro_aportantes_x, $motivo);
                    
                    DB::commit();
                    
                    // Guardado
                    $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionalesExterno');
                    $this->dispatch('PlanillaEliminada'); 
                
            }
        
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error');
        }
    }
    public function crear_planilla_eliminada($id_comp, $id_institucion, $id_mes, $gestion, $total_salario_cotizable, $nro_aportantes, $motivo){
        DB::beginTransaction();
        try {
            $comp_eliminado = ComprobanteEliminado::create([
                'id_usuario_elimina' => auth()->user()->id,
                'id_comprobante' => $id_comp,
                'id_institucion' => $id_institucion,
                'id_mes' => $id_mes,
                'gestion' => $gestion,
                'total_salario_cotizable' => $total_salario_cotizable,
                'nro_aportantes' => $nro_aportantes,
                'motivo_eliminacion' => $motivo,
                'id_user_created' => auth()->user()->id,
            ]);

           DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error');
        }
    }
}
