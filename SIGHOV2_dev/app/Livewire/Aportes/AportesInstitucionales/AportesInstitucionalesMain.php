<?php

namespace App\Livewire\Aportes\AportesInstitucionales;

use App\Helpers\NumberToWords;
use Livewire\Component;
use App\Models\Aportes\Comprobante;
use App\Models\Aportes\Institucion;
use App\Models\Aportes\FormaPago;
use App\Models\Aportes\ClaseAportante;
use App\Models\Aportes\Mes;
use Livewire\Attributes\On;
use Carbon\Carbon;
use App\Models\Aportes\Ufv;
use App\Models\Aportes\TasaInteres;
use App\Models\Aportes\AporteInstitucionalTemp;
use App\Models\Aportes\AporteInstitucional;
use App\Models\Aportes\PlanillaBajaMedica;
use App\Models\Aportes\ComprobanteEliminado;
use App\Models\Administracion\Estado;
use App\Models\Administracion\RolUsuario;
use App\Models\Administracion\NotificacionUsuarios;
use App\Models\Administracion\Role;
use App\Mail\Aportes\PlanillasIntitucionalesLiquidadaMailable;
use App\Services\DarBajaCesante;
use Illuminate\Support\Facades\Mail;
use App\Services\QRCodeService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use App\Models\Administracion\Persona;
use App\Models\User;
use Illuminate\Support\Facades\Log;


class AportesInstitucionalesMain extends Component
{
    protected $listeners = ['crear_liquidacion', 'descargarPdf', 'descargarCopiaPdf'];

    //--------------
    public $compId;
    public $modal;
    public $modalPreliq;
    public $compId_eliminar;

    public $comprobante;
    public $comprobanteId;

    public $representanteLegal;
    public $dato_solo_fecha = null;

    // variables para forma de pago
    public $monto_deduccion_r = 0;
    public $deduccion_descripcion_r;
    public $monto_excedente = 0;
    public $excedente_descripcion;
    public $nro_compro_anterior;
    public $total_compro_anterior;
    public $fecha_pago = null;
    public $tipo_pago;
    public $nro_pago_forma;
    public $total_forma_pago;
    public $representanteLegalNombre;

    // atributos de comprobante
    public $id_comp;
    public $id_institucion;
    public $nit;
    public $id_mes;
    public $gestion;
    public $tgn;
    public $beneficio_social;
    public $planilla_adicional;
    public $reintegro;
    // atributos de institucion
    public $nombre_institucion;
    public $nombre_institucion_select;
    // atributos de mes
    public $nombre_mes;
    public $m_tipo_clase;

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
    public $dato_fecha_liquidacion = null;
    public $fecha_inicio_plazo;
    public $fecha_fin_plazo;
    public $valor_ufv_plazo;
    public $valor_ufv_pago;
    public $monto_demasia = 0;
    public $monto_deduccion = 0;
    public $demasia_descripcion = '';
    public $deduccion_descripcion = '';

    public $presenta_planilla = true;
    public $numero_mes;
    public $mostrarContenido = false;
    public $total_comprobante = 0;
    public $monto_bajas_medicas = 0;
    public $observacion = ' ';
    public $fecha_liquidacion_baja_medica = null;
    public $nro_registro = 0;
    public $m_nro_registro = '';
    public $anio_sistema = 0;
    public $anio_registrado = 0;
    public $forma_pago_c;
    public $diferencia_pago_liquidacion;
    public $m_dato_fecha_liquidacion;
    public $mostrar_preliquidacion;
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
    public $m_tasa_clase;
    public $estado_comprobante;
    public $fecha_liquidado;
    public $fecha_liquidado_ant;
    public $reintegro_ant;
    public $planilla_adicional_ant;
    public $beneficio_social_ant;
    public $user_created_name, $user_updated_name, $fecha_user_created, $fecha_user_updated, $nro_compro_anterior_m;

    public $importe;
    public $retroactivo, $compId_validar;

    public function render()
    {
        return view('livewire.Aportes.aportes-institucionales.aportes-institucionales-main');
    }

    public $institucion = [], $formapago =  [], $mes = [], $clase = [];
    public function mount()
    {
        $this->institucion = Institucion::all();
        $this->formapago = FormaPago::all();
        $this->mes = Mes::all();
        $this->clase = ClaseAportante::all();
    }


    public function crear_liquidacion($confirmado = null)
    {
        if ($this->dato_fecha_liquidacion) {
            $fecha_formateada = Carbon::parse($this->dato_fecha_liquidacion)->format('Y-m-d');
            $existe_ufv = UFV::where('fecha', $fecha_formateada)->first();
        } else {
            $existe_ufv = null;
        }
        DB::beginTransaction();
        try {

            // mientras el confirmadso este en null este solo realiza los calculos mas no los guarda hasta que se confirme con el boton
            // de lo contrario se limipian los datos

            if ($confirmado === null && $existe_ufv != null) {
                // Realiza las operaciones
                $this->tasa = 0;
                $this->dias_mora = 0;
                $this->subtotal_recargos = 0;
                $this->factor_activo = 1;

                if ($this->dato_fecha_liquidacion != null) {

                    if ($this->forma_pago_c != null && $this->fecha_pago != null) {
                        $this->fecha_inicio_plazo = Carbon::createFromDate($this->gestion, $this->numero_mes, 1)->format('Y-m-d');
                        $this->fecha_fin_plazo = Carbon::createFromDate($this->gestion, $this->numero_mes + 1, 1)
                            ->endOfMonth()
                            ->format('Y-m-d');

                        $this->fecha_inicio_plazo = Carbon::parse($this->fecha_inicio_plazo);
                        $this->fecha_fin_plazo = Carbon::parse($this->fecha_fin_plazo);

                        $this->m_dato_fecha_liquidacion = Carbon::parse($this->dato_fecha_liquidacion)->format('d/m/Y');

                        $this->fecha_pago = Carbon::parse($this->fecha_pago);

                        $this->anio_sistema = Carbon::now()->year;
                        $this->anio_registrado = $this->fecha_pago->year;

                        $this->valor_ufv_plazo = $this->obtenerUFV($this->fecha_fin_plazo);
                        $this->valor_ufv_pago = $this->obtenerUFV($this->fecha_pago);

                        if (!$this->planilla_adicional && !$this->beneficio_social && !$this->reintegro && !$this->retroactivo) {
                            if (!($this->fecha_pago >= $this->fecha_inicio_plazo && $this->fecha_pago <= $this->fecha_fin_plazo)) {
                                if ($this->valor_ufv_pago !== null && $this->valor_ufv_plazo !== null) {
                                    $this->dias_mora = $this->fecha_pago->diffInDays($this->fecha_fin_plazo);
                                    $this->factor_activo = round($this->valor_ufv_plazo / $this->valor_ufv_pago, 6);
                                    $this->tasa = round($this->obtenerTasaInteres() / 100, 4);
                                } else {
                                    $this->dispatch('AlertUFV');
                                }
                            }
                        }

                        $this->importe_aporte_patronal = round($this->importe_aporte_patronal, 2);

                        $this->actualizacion_aporte = round($this->salario_cotizable - $this->salario_cotizable * $this->factor_activo, 2);

                        $this->importe_actualizacion_aporte = round($this->actualizacion_aporte * $this->tasa_clase, 2);

                        //SubTotal(A)
                        $this->subtotal_liquidacion = round($this->importe_actualizacion_aporte + $this->importe_aporte_patronal + $this->monto_excedente + $this->total_compensacion, 2);

                        $this->intereses = round((($this->tasa * $this->subtotal_liquidacion) / 365) * $this->dias_mora, 2);
                        $this->multas_sin_intereses = round($this->intereses * $this->tasa_multas, 2);
                        $this->multas_no_planilla = 0;

                        //if (!$this->planilla_adicional && !$this->beneficio_social ) {
                        if (!$this->presenta_planilla) {
                            $this->multas_no_planilla = round($this->subtotal_liquidacion * $this->tasa_multa_no_planilla, 2);
                        }
                        //}
                        //SubTotal(B)
                        $this->subtotal_recargos = round($this->intereses + $this->multas_sin_intereses + $this->multas_no_planilla, 2);

                        if ($this->nombre_clase == 'TGN') {
                            $this->descripcion = 'Menos el 5% para el Ministerio de Salud';
                            $this->ministerio = round($this->importe_aporte_patronal * 0.05, 2);
                        }
                        //SubTotal(C)
                        if ($this->tgn == true) {
                            $this->descripcion = 'Menos el 5% para el ministerio de salud';
                            $this->ministerio = round($this->importe_aporte_patronal * 0.05, 2);
                        }

                        $this->subtotal_deducciones = $this->ministerio + $this->monto_deduccion_r;


                        // TOTAL COMPROBANTE
                        $this->total_a_b_c = round($this->subtotal_liquidacion + $this->subtotal_recargos - $this->subtotal_deducciones, 2);
                        $this->total_comprobante = $this->total_a_b_c - $this->monto_bajas_medicas;
                        $this->nro_registro = $this->obtenerUltimoNroComprobante();

                        $this->diferencia_pago_liquidacion = round($this->forma_pago_c - $this->total_a_b_c, 2);


                        // Dispara el evento para mostrar la confirmación de SweetAlert.
                        $this->dispatch('guardarComprobante');
                    } else {
                        $this->dispatch('noFormaPago');
                    }
                } elseif ($this->dato_fecha_liquidacion == null || '') {
                    $this->dispatch('IntroduzcaFecha');
                }
            } elseif ($confirmado && $existe_ufv != null) {
                // Si el usuario confirma, se  guardan los datos en la base de datos.
                $liquidar = Comprobante::find($this->compId);
                if ($liquidar) {
                    $liquidar->nro_registro_comprobante = $this->nro_registro;
                    $liquidar->id_institucion = $this->id_institucion;
                    $liquidar->fecha_liquidacion = Carbon::parse($this->dato_fecha_liquidacion)->format('Y-m-d');
                    $liquidar->planilla = $this->presenta_planilla;
                    $liquidar->importe_aporte_patronal = $this->importe_aporte_patronal;
                    $liquidar->factor_actual = $this->factor_activo;
                    $liquidar->actualizacion_aporte = $this->actualizacion_aporte;
                    $liquidar->importe_actualizacion_aporte = $this->importe_actualizacion_aporte;
                    $liquidar->subtotal_liquidacion = $this->subtotal_liquidacion;
                    $liquidar->tasa_interes = $this->tasa;
                    $liquidar->dias_mora = $this->dias_mora;
                    $liquidar->importe_intereses = $this->intereses;
                    $liquidar->importe_multa_interes = $this->multas_sin_intereses;
                    $liquidar->importe_multa_no_planilla = $this->multas_no_planilla;
                    $liquidar->subtotal_recargos = $this->subtotal_recargos;

                    $liquidar->monto_total_comprobante = $this->total_a_b_c;
                    $liquidar->monto_bajas_medicas = $this->monto_bajas_medicas;
                    $liquidar->fecha_liquidacion_baja_medica = $this->fecha_liquidacion_baja_medica;
                    $liquidar->total_comprobante = $this->total_comprobante;
                    $liquidar->id_estado = 4;
                    $liquidar->subtotal_deducciones = $this->subtotal_deducciones;
                    $liquidar->ministerio = $this->ministerio;
                    $liquidar->observacion = $this->observacion;
                    $liquidar->diferencia_pago_liquidacion = $this->diferencia_pago_liquidacion;
                    $liquidar->fecha_liquidado = Carbon::now();
                    $liquidar->id_user_updated = auth()->user()->id;

                    $liquidar->observacion = 'La diferencia es: ' . $this->diferencia_pago_liquidacion . ' Adj. a obs: ' . $this->observacion;

                    $liquidar->save();
                    DB::commit();

                    $this->dispatch('liqCreada');
                    $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionales');

                    $rol = Role::where('name', 'Encargado Aportes')->first();
                    $inst = Institucion::find($this->id_institucion);

                    $compro = Comprobante::where('id', $this->compId)
                        ->with('mesComprobante')
                        ->first();

                    // PARA QUE SE DESCARGUE EL PDF
                    $this->js('window.open("/aportes/pdf/' . $this->compId . '", "_blank")');

                    if ($inst->id_usuario_responsable != null) {
                        $nuevaNotificacion = NotificacionUsuarios::create([
                            'estado' => 'NO LEIDO',
                            'prioridades' => 1,
                            'id_usu_emisor' => auth()->user()->id,
                            'id_usu_receptor' => $inst->id_usuario_responsable,
                            'fecha_hora_enviada' => Carbon::now(),
                            'asunto' => 'APORTES - LA PLANILLA SE LIQUIDÓ CORRECTAMENTE',
                            'mensaje' => 'La planilla correspondiente al mes de ' . $compro->mesComprobante->mes . ' de ' . $compro->gestion . ' fue liquidada correctamente.',
                        ]);
                    }

                    // En esta parte se esta guardando el PDF COPIA al FTP 
                    //$this->js('window.open("/aportes/pdfcopia/' . $this->compId . '", "_blank")');
                    // Con la accion del js se descarga en formato pdf el comprobante   
                }
            } else {
                // Si el usuario cancela el registro de comprobante
                DB::rollBack();
                if ($existe_ufv == null) {
                    $this->reset('dato_fecha_liquidacion');
                    $this->dispatch('no_ufv');
                } else {
                    $this->dispatch('liqCancelado');
                }
            }
            $this->dato_fecha_liquidacion = " ";
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error');
        }
    }


    public function obtenerTasaInteres()
    {
        $obtener_tasa = TasaInteres::where('estado', true)->orderBy('id', 'desc')->first();
        $dato_valor = $obtener_tasa->valor;
        return $dato_valor;
    }

    public function obtenerUFV($fecha)
    {
        $obtener_ufv = Ufv::where('fecha', $fecha)->where('estado', true)->first();
        if ($obtener_ufv) {
            return $obtener_ufv->valor;
        } else {
            return null;
        }
    }

    public function obtenerUltimoNroComprobante()
    {
        $currentYear = Carbon::now()->year;
        $ultimoRegistro = Comprobante::whereNotNull('nro_registro_comprobante')
            //where('id_estado', 4)
            //  ->whereYear('fecha_liquidacion', $currentYear) // Aseguramos que la fecha sea del año actual
            ->orderByRaw("EXTRACT(YEAR FROM fecha_liquidacion) DESC") // Ordenar por año (descendente)
            //  ->orderBy('nro_registro_comprobante', 'desc') // Luego por el número de comprobante (descendente)
            // ->orderBy('id', 'desc')
            ->orderBy('nro_registro_comprobante', 'desc')
            ->first();

        if (!$ultimoRegistro) {
            return 1;
        }
        // dd($ultimoRegistro); prueba
        return $ultimoRegistro->nro_registro_comprobante + 1;
    }



    #[On('verPlanilla')]
    public function recibirComprobante($data)
    {
        $this->compId = $data['comprobanteId'];
        $this->modal = $data['modalEstado'];
        $this->dispatch('openModal');
    }
    #[On('preliquidacion')]
    public function recibirPreLiquidacion($data)
    {
        $this->reset();
        $this->compId = $data['comprobanteId'];
        $this->dispatch('openModalPreLiq');
    }

    #[On('liquidacionOn')]
    public function recibirLiquidacion($data)
    {

        $this->compId = $data['comprobanteId'];
        $this->dispatch('openModalLiquidacion');
    }

    #[On('aportantesGrupo')]
    public function mostrarAportantes($data)
    {
        $this->compId = $data['comprobanteId'];

        $this->mostrarComprobante($this->compId);
        $this->dispatch('openModalAportantes');
    }
    #[On('aportantesGrupoTemp')]
    public function mostrarAportantesTemp($data)
    {
        $this->compId = $data['comprobanteId'];

        $this->mostrarComprobante($this->compId);
        $this->dispatch('openModalAportantesTemp');
    }

    #[On('eliminarPlanillas')]
    public function eliminarplanillacargada($data)
    {
        $this->compId_eliminar = $data['comprobanteId'];

        $comprobante = Comprobante::find($this->compId_eliminar);
        if ($comprobante->id_institucion) {
            $institucionActualnombre = Institucion::find($comprobante->id_institucion);
            $this->nombre_institucion_select = $institucionActualnombre->nombre;

            $this->dispatch('ConfirmareliminarPlanillas', id_comprobante: $this->compId_eliminar, nombre_institu: $this->nombre_institucion_select);
        }
    }

    public $tipo_planilla;

    #[On('EliminarComprobanteSeleccionado')]
    public function EliminarComprobanteSubido($id_comprobante_eliminar, $motivo)
    {

        $id_comp = $id_comprobante_eliminar;

        DB::beginTransaction();
        try {
            $eliminar_com = Comprobante::find($id_comprobante_eliminar);

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


            if ($reintegro == false && $beneficio_social == false && $planilla_adicional == false  && $retroactivo == false) {
                $this->tipo_planilla = 0;
            } elseif ($reintegro == true) {
                $this->tipo_planilla = 1;
            } elseif ($beneficio_social == true) {
                $this->tipo_planilla = 2;
            } elseif ($planilla_adicional == true) {
                $this->tipo_planilla = 3;
            } elseif ($retroactivo == true) {
                $this->tipo_planilla = 4;
            }

            if ($this->tipo_planilla == 0 || $this->tipo_planilla ==  4) {

                //  UMSA
                if ($id_institucion == 114) {

                    // Aqui empieza la eliminacion de planillas por UMSA 
                    if ($estado_umsa == true) {

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
                        $this->crear_planilla_eliminada(
                            $id_comp_doc,
                            2,
                            $id_mes_doc,
                            $gestion_doc,
                            $total_salario_cotizable_doc,
                            $nro_aportantes_doc,
                            $motivo
                        );

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
                        $this->crear_planilla_eliminada(
                            $id_comp_adm,
                            6,
                            $id_mes_adm,
                            $gestion_adm,
                            $total_salario_cotizable_adm,
                            $nro_aportantes_adm,
                            $motivo
                        );


                        // Eliminar umsa general 
                        $id_comp_umsa = $eliminar_com->id;
                        $id_mes_umsa = $eliminar_com->id_mes;
                        $gestion_umsa = $eliminar_com->gestion;
                        $total_salario_cotizable_umsa = $eliminar_com->total_salario_cotizable;
                        $nro_aportantes_umsa = intval(($eliminar_com->cantidad_eventuales ?? 0) + ($eliminar_com->cantidad_permanentes ?? 0));

                        $eliminarAporteUmsa = AporteInstitucionalTemp::where('id_comp', $id_comp_umsa)
                            ->delete();

                        $eliminar_com->delete();
                        $this->crear_planilla_eliminada(
                            $id_comp_umsa,
                            114,
                            $id_mes_umsa,
                            $gestion_umsa,
                            $total_salario_cotizable_umsa,
                            $nro_aportantes_umsa,
                            $motivo
                        );

                        DB::commit();

                        // Guardado
                        $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionales');
                        $this->dispatch('PlanillaEliminada');
                    } elseif ($estado_umsa == false) {

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

                        if ($comp_doc !== null) {

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
                            $this->crear_planilla_eliminada(
                                $id_comp_doc,
                                2,
                                $id_mes_doc,
                                $gestion_doc,
                                $total_salario_cotizable_doc,
                                $nro_aportantes_doc,
                                $motivo
                            );


                            // Eliminar umsa general

                            $id_comp_umsa = $eliminar_com->id;
                            $id_mes_umsa = $eliminar_com->id_mes;
                            $gestion_umsa = $eliminar_com->gestion;
                            $total_salario_cotizable_umsa = $eliminar_com->total_salario_cotizable;
                            $nro_aportantes_umsa = intval(($eliminar_com->cantidad_eventuales ?? 0) + ($eliminar_com->cantidad_permanentes ?? 0));

                            $eliminarAporteUmsa = AporteInstitucionalTemp::where('id_comp', $id_comp_umsa)
                                ->delete();

                            $eliminar_com->delete();
                            $this->crear_planilla_eliminada(
                                $id_comp_umsa,
                                114,
                                $id_mes_umsa,
                                $gestion_umsa,
                                $total_salario_cotizable_umsa,
                                $nro_aportantes_umsa,
                                $motivo
                            );

                            DB::commit();

                            // Guardado
                            $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionales');
                            $this->dispatch('PlanillaEliminada');
                        } elseif ($comp_adm !== null) {
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
                            $this->crear_planilla_eliminada(
                                $id_comp_adm,
                                6,
                                $id_mes_adm,
                                $gestion_adm,
                                $total_salario_cotizable_adm,
                                $nro_aportantes_adm,
                                $motivo
                            );


                            // Eliminar umsa general

                            $id_comp_umsa = $eliminar_com->id;
                            $id_mes_umsa = $eliminar_com->id_mes;
                            $gestion_umsa = $eliminar_com->gestion;
                            $total_salario_cotizable_umsa = $eliminar_com->total_salario_cotizable;
                            $nro_aportantes_umsa = intval(($eliminar_com->cantidad_eventuales ?? 0) + ($eliminar_com->cantidad_permanentes ?? 0));



                            $eliminarAporteUmsa = AporteInstitucionalTemp::where('id_comp', $id_comp_umsa)
                                ->delete();

                            $eliminar_com->delete();
                            $this->crear_planilla_eliminada(
                                $id_comp_umsa,
                                114,
                                $id_mes_umsa,
                                $gestion_umsa,
                                $total_salario_cotizable_umsa,
                                $nro_aportantes_umsa,
                                $motivo
                            );

                            DB::commit();

                            // Guardado
                            $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionales');
                            $this->dispatch('PlanillaEliminada');
                        }
                    } elseif ($estado_umsa == null) {
                        // Eliminar umsa general 
                        $id_comp_umsa = $eliminar_com->id;
                        $id_mes_umsa = $eliminar_com->id_mes;
                        $gestion_umsa = $eliminar_com->gestion;
                        $total_salario_cotizable_umsa = $eliminar_com->total_salario_cotizable;
                        $nro_aportantes_umsa = intval(($eliminar_com->cantidad_eventuales ?? 0) + ($eliminar_com->cantidad_permanentes ?? 0));

                        $eliminarAporteUmsa = AporteInstitucionalTemp::where('id_comp', $id_comp_umsa)
                            ->delete();

                        $eliminar_com->delete();
                        $this->crear_planilla_eliminada(
                            $id_comp_umsa,
                            114,
                            $id_mes_umsa,
                            $gestion_umsa,
                            $total_salario_cotizable_umsa,
                            $nro_aportantes_umsa,
                            $motivo
                        );

                        DB::commit();

                        // Guardado
                        $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionales');
                        $this->dispatch('PlanillaEliminada');
                    }
                } elseif ($id_institucion == 2) {

                    $eliminar_comp_id = $eliminar_com->id;

                    $comp_umsa = Comprobante::where('id_institucion', 114)
                        ->where('id_mes',  $eliminar_com->id_mes)
                        ->where('gestion',  $eliminar_com->gestion)
                        ->where('reintegro',  $eliminar_com->reintegro)
                        ->where('beneficio_social',  $eliminar_com->beneficio_social)
                        ->where('planilla_adicional',  $eliminar_com->planilla_adicional)
                        ->where('retroactivo',  $eliminar_com->retroactivo)
                        ->first();


                    if ($comp_umsa) {
                        $v_estado_umsa = $comp_umsa->estado_umsa;
                        if ($v_estado_umsa == true) {
                            // Actualizar el estado a FALSE de Comp UMSA y debe eliminarse  Doc 
                            $eventuales = $eliminar_com->cantidad_eventuales;
                            $permanentes = $eliminar_com->cantidad_permanentes;
                            $total_salario_cotizable = $eliminar_com->total_salario_cotizable;
                            $importe = $eliminar_com->importe_aporte_patronal;
                            $total_compensacion = $eliminar_com->total_compensacion;

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

                            $eventuales_v = $comp_umsa->cantidad_eventuales - $eventuales;
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
                            $this->crear_planilla_eliminada(
                                $eliminar_comp_id,
                                2,
                                $id_mes_doc,
                                $gestion_doc,
                                $total_salario_cotizable_doc,
                                $nro_aportantes_doc,
                                $motivo
                            );

                            DB::commit();

                            // Guardado
                            $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionales');
                            $this->dispatch('PlanillaEliminada');
                        } elseif ($v_estado_umsa == false) {
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
                            $this->crear_planilla_eliminada(
                                $id_comp_doc,
                                2,
                                $id_mes_doc,
                                $gestion_doc,
                                $total_salario_cotizable_doc,
                                $nro_aportantes_doc,
                                $motivo
                            );


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

                            $this->crear_planilla_eliminada(
                                $id_comp_umsa,
                                114,
                                $id_mes_umsa,
                                $gestion_umsa,
                                $total_salario_cotizable_umsa,
                                $nro_aportantes_umsa,
                                $motivo
                            );

                            DB::commit();

                            // Guardado
                            $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionales');
                            $this->dispatch('PlanillaEliminada');
                        }
                    } else {

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

                        $this->crear_planilla_eliminada(
                            $id_comp_doc,
                            2,
                            $id_mes_doc,
                            $gestion_doc,
                            $total_salario_cotizable_doc,
                            $nro_aportantes_doc,
                            $motivo
                        );

                        DB::commit();

                        // Guardado
                        $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionales');
                        $this->dispatch('PlanillaEliminada');
                    }
                } elseif ($id_institucion == 6) {


                    $eliminar_comp_id = $eliminar_com->id;

                    $comp_umsa = Comprobante::where('id_institucion', 114)
                        ->where('id_mes',  $eliminar_com->id_mes)
                        ->where('gestion',  $eliminar_com->gestion)
                        ->where('reintegro',  $eliminar_com->reintegro)
                        ->where('beneficio_social',  $eliminar_com->beneficio_social)
                        ->where('planilla_adicional',  $eliminar_com->planilla_adicional)
                        ->where('retroactivo',  $eliminar_com->retroactivo)
                        ->first();


                    if ($comp_umsa) {
                        $v_estado_umsa = $comp_umsa->estado_umsa;
                        if ($v_estado_umsa == true) {
                            // Actualizar el estado a FALSE de Comp UMSA, para cualquier caso que se elimine; Adm  
                            $eventuales = $eliminar_com->cantidad_eventuales;
                            $permanentes = $eliminar_com->cantidad_permanentes;
                            $total_salario_cotizable = $eliminar_com->total_salario_cotizable;
                            $importe = $eliminar_com->importe_aporte_patronal;
                            $total_compensacion = $eliminar_com->total_compensacion;

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

                            $eventuales_v = $comp_umsa->cantidad_eventuales - $eventuales;
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

                            $this->crear_planilla_eliminada(
                                $eliminar_comp_id,
                                6,
                                $id_mes_adm,
                                $gestion_adm,
                                $total_salario_cotizable_adm,
                                $nro_aportantes_adm,
                                $motivo
                            );

                            DB::commit();

                            // Guardado
                            $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionales');
                            $this->dispatch('PlanillaEliminada');
                        } elseif ($v_estado_umsa == false) {
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
                            $this->crear_planilla_eliminada(
                                $id_comp_adm,
                                6,
                                $id_mes_adm,
                                $gestion_adm,
                                $total_salario_cotizable_adm,
                                $nro_aportantes_adm,
                                $motivo
                            );


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

                            $this->crear_planilla_eliminada(
                                $id_comp_umsa,
                                114,
                                $id_mes_umsa,
                                $gestion_umsa,
                                $total_salario_cotizable_umsa,
                                $nro_aportantes_umsa,
                                $motivo
                            );

                            DB::commit();

                            // Guardado
                            $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionales');
                            $this->dispatch('PlanillaEliminada');
                        }
                    } else {

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

                        $this->crear_planilla_eliminada(
                            $id_comp_adm,
                            6,
                            $id_mes_adm,
                            $gestion_adm,
                            $total_salario_cotizable_adm,
                            $nro_aportantes_adm,
                            $motivo
                        );

                        DB::commit();

                        // Guardado
                        $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionales');
                        $this->dispatch('PlanillaEliminada');
                    }
                } else {

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

                    $this->crear_planilla_eliminada(
                        $id_comp_x,
                        $id_insti_x,
                        $id_mes_x,
                        $gestion_x,
                        $total_salario_cotizable_x,
                        $nro_aportantes_x,
                        $motivo
                    );

                    DB::commit();

                    // Guardado
                    $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionales');
                    $this->dispatch('PlanillaEliminada');
                }
            } else {
                // Aqui eliminamos planillas diferentes a UMSA - ADM UMSA - DOC UMSA
                $id_comp_x = $eliminar_com->id;
                $id_insti_x = $eliminar_com->id_institucion;
                $id_mes_x = $eliminar_com->id_mes;
                $gestion_x = $eliminar_com->gestion;
                $total_salario_cotizable_x = $eliminar_com->total_salario_cotizable;
                $nro_aportantes_x = intval(($eliminar_com->cantidad_eventuales ?? 0) + ($eliminar_com->cantidad_permanentes ?? 0));

                $eliminarAporteTempX = AporteInstitucionalTemp::where('id_comp', $id_comp)
                    ->delete();

                $eliminar_com->delete();

                $this->crear_planilla_eliminada(
                    $id_comp_x,
                    $id_insti_x,
                    $id_mes_x,
                    $gestion_x,
                    $total_salario_cotizable_x,
                    $nro_aportantes_x,
                    $motivo
                );

                DB::commit();

                // Guardado
                $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionales');
                $this->dispatch('PlanillaEliminada');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error');
        }
    }

    public function crear_planilla_eliminada($id_comp, $id_institucion, $id_mes, $gestion, $total_salario_cotizable, $nro_aportantes, $motivo)
    {
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





    #[On('validarPlanilla')]
    public function ValidarPlanillaExcel($data)
    {
        try {
            $this->compId_validar = $data['comprobanteId'];
            $comprobante = Comprobante::find($this->compId_validar);
            $institucionActualnombre = Institucion::find($comprobante->id_institucion);
            $this->nombre_institucion_select = $institucionActualnombre->nombre;

            $this->dispatch('ConfirmarValidarPlanillas', id_comprobante: $this->compId_validar, nombre_institu: $this->nombre_institucion_select);
        } catch (\Throwable $th) {
            $this->dispatch('error');
        }
    }

    #[On('ValidarComprobanteSeleccionado')]
    public function ValidarComprobanteSubido($id_comprobante_validar)
    {
        DB::beginTransaction();
        try {

            $ComprobanteTemp = Comprobante::find($id_comprobante_validar);
            $id_insti = $ComprobanteTemp->id_institucion;
            //dd($id_insti);

            //Aqui verificamos que es comprobante de la UMSA
            if ($id_insti == 114) {
                //dd('umsa');
                if ($ComprobanteTemp) {
                    $comp_docentes = Comprobante::where([
                        'id_institucion' => 2,
                        'id_mes' => $ComprobanteTemp->id_mes,
                        'gestion' => $ComprobanteTemp->gestion,
                        'reintegro' => $ComprobanteTemp->reintegro,
                        'beneficio_social' => $ComprobanteTemp->beneficio_social,
                        'planilla_adicional' => $ComprobanteTemp->planilla_adicional,
                        'retroactivo' => $ComprobanteTemp->retroactivo,
                    ])->first();
                    $id_comp_docentes = $comp_docentes ? $comp_docentes->id : null;

                    $comp_administrativos = Comprobante::where([
                        'id_institucion' => 6,
                        'id_mes' => $ComprobanteTemp->id_mes,
                        'gestion' => $ComprobanteTemp->gestion,
                        'reintegro' => $ComprobanteTemp->reintegro,
                        'beneficio_social' => $ComprobanteTemp->beneficio_social,
                        'planilla_adicional' => $ComprobanteTemp->planilla_adicional,
                        'retroactivo' => $ComprobanteTemp->retroactivo,
                    ])->first();
                    $id_comp_administrativos = $comp_administrativos ? $comp_administrativos->id : null;

                    if ($id_comp_docentes !== null && $id_comp_administrativos !== null) {

                        // dd($id_comp_docentes,$id_comp_administrativos );


                        // Actualizar validacion UMSA general
                        $ComprobanteTemp->id_estado = 2;
                        $ComprobanteTemp->fecha_validado = Carbon::now();
                        $ComprobanteTemp->id_user_updated = auth()->user()->id;
                        $ComprobanteTemp->save();

                        // INICIO DE NOTIFICACION 
                        $institucion = $ComprobanteTemp->id_institucion;
                        $datosInstitucion = Institucion::find($institucion);
                        if ($datosInstitucion->id_usuario_responsable != null) {
                            $nuevaNotificacion = NotificacionUsuarios::create([
                                'estado' => 'NO LEIDO',
                                'prioridades' => 1,
                                'id_usu_emisor' => auth()->user()->id,
                                'id_usu_receptor' => $datosInstitucion->id_usuario_responsable,
                                'fecha_hora_enviada' => Carbon::now(),
                                'asunto' => 'APORTES - LA PLANILLA SE VALIDÓ CORRECTAMENTE',
                                'mensaje' => 'La planilla para su institución: ' . $datosInstitucion->nombre . ' fue validada correctamente.',
                            ]);
                        }
                        // FIN DE NOTIFICACION 

                        $AportantesTemp = AporteInstitucionalTemp::where('id_comp', $ComprobanteTemp->id)->get();

                        $claseAportante = Institucion::where('id', $ComprobanteTemp->id_institucion)
                            ->with('claseAportanteInstitucion')
                            ->first();

                        if ($claseAportante->claseAportanteInstitucion->nombre != 'RENTISTA') {


                            foreach ($AportantesTemp as $aportantes) {
                                $totalAporteSumado = $aportantes->total_aporte + $aportantes->compensacion_salario_minimo * 0.1;

                                $AportantesValidados = AporteInstitucional::create([
                                    'id_persona' => $aportantes->id_persona,
                                    'id_institucion' => $aportantes->id_institucion,
                                    'item' => $aportantes->item,
                                    'cargo' => $aportantes->cargo,
                                    'area' => $aportantes->area,
                                    'tipo_trabajador' => $aportantes->tipo_trabajador,
                                    'carga_horaria' => $aportantes->carga_horaria,
                                    'dias_trabajados' => $aportantes->dias_trabajados,
                                    'total_ganado' => $aportantes->total_ganado,
                                    'total_ganado_dia' => $aportantes->total_ganado_dia,
                                    'total_aporte' => $totalAporteSumado,
                                    'gestion' => $aportantes->gestion,
                                    'reintegro_normal' => $aportantes->reintegro_normal,
                                    'id_mes' => $aportantes->id_mes,
                                    'reintegro_incr_salarial' => $aportantes->reintegro_incr_salarial,
                                    'beneficio_social' => $aportantes->beneficio_social,
                                    'planilla_adicional' => $aportantes->planilla_adicional,
                                    'retroactivo' => $aportantes->retroactivo,
                                    'jubilado' => $aportantes->jubilado,
                                    'fecha_ingreso' => $aportantes->fecha_ingreso,
                                    'fecha_novedad_retiro_suspension' => $aportantes->fecha_novedad_retiro_suspension,
                                    'motivo_novedad_retiro_suspension' => $aportantes->motivo_novedad_retiro_suspension,
                                    'parentesco_rentista' => $aportantes->parentesco_rentista,
                                    'tipo_renta' => $aportantes->tipo_renta,
                                    'aporte_planilla' => $aportantes->aporte_planilla,
                                    'compensacion_salario_minimo' => $aportantes->compensacion_salario_minimo,
                                    'estado_compensacion' => $aportantes->estado_compensacion,
                                    'umsa_doc' => $aportantes->umsa_doc,
                                    'umsa_adm' => $aportantes->umsa_adm,
                                    'id_comp' => $aportantes->id_comp,
                                    'id_user_created' => auth()->user()->id,
                                ]);
                                AporteInstitucionalTemp::where('id', $aportantes->id)->update([
                                    'estado' => 1,
                                ]);





                                /*if($aportantes->fecha_novedad_retiro_suspension != null){
                                    $servicioBaja = new DarBajaCesante();
                                    $servicioBaja->bajaDefinitiva($aportantes->id_persona, $ComprobanteTemp->id_institucion,
                                        $aportantes->fecha_novedad_retiro_suspension, $aportantes->motivo_novedad_retiro_suspension, $aportantes->id_mes, $aportantes->gestion);
                                }*/
                            }
                        }
                        $this->validar_umsa_docentes($id_comp_docentes);
                        $this->validar_umsa_administrativos($id_comp_administrativos);

                        DB::commit();
                        $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionales');
                        $this->dispatch('SeValidoLaPlanilla');
                    } elseif ($id_comp_docentes === null && $id_comp_administrativos === null) {
                        // Aqui valida planilla unica de UMSA
                        if ($ComprobanteTemp) {

                            $ComprobanteTemp->id_estado = 2;
                            $ComprobanteTemp->fecha_validado = Carbon::now();
                            $ComprobanteTemp->id_user_updated = auth()->user()->id;
                            $ComprobanteTemp->save();

                            // INICIO DE NOTIFICACION 
                            $institucion = $ComprobanteTemp->id_institucion;
                            $datosInstitucion = Institucion::find($institucion);
                            if ($datosInstitucion->id_usuario_responsable != null) {
                                $nuevaNotificacion = NotificacionUsuarios::create([
                                    'estado' => 'NO LEIDO',
                                    'prioridades' => 1,
                                    'id_usu_emisor' => auth()->user()->id,
                                    'id_usu_receptor' => $datosInstitucion->id_usuario_responsable,
                                    'fecha_hora_enviada' => Carbon::now(),
                                    'asunto' => 'APORTES - LA PLANILLA SE VALIDÓ CORRECTAMENTE',
                                    'mensaje' => 'La planilla para su institución: ' . $datosInstitucion->nombre . ' fue validada correctamente.',
                                ]);
                            }
                            // FIN DE NOTIFICACION 

                            $AportantesTemp = AporteInstitucionalTemp::where('id_comp', $ComprobanteTemp->id)->get();

                            $claseAportante = Institucion::where('id', $ComprobanteTemp->id_institucion)
                                ->with('claseAportanteInstitucion')
                                ->first();
                        }

                        if ($claseAportante->claseAportanteInstitucion->nombre != 'RENTISTA') {


                            foreach ($AportantesTemp as $aportantes) {
                                $totalAporteSumado = $aportantes->total_aporte + $aportantes->compensacion_salario_minimo * 0.1;

                                $AportantesValidados = AporteInstitucional::create([
                                    'id_persona' => $aportantes->id_persona,
                                    'id_institucion' => $aportantes->id_institucion,
                                    'item' => $aportantes->item,
                                    'cargo' => $aportantes->cargo,
                                    'area' => $aportantes->area,
                                    'tipo_trabajador' => $aportantes->tipo_trabajador,
                                    'carga_horaria' => $aportantes->carga_horaria,
                                    'dias_trabajados' => $aportantes->dias_trabajados,
                                    'total_ganado' => $aportantes->total_ganado,
                                    'total_ganado_dia' => $aportantes->total_ganado_dia,
                                    'total_aporte' => $totalAporteSumado,
                                    'gestion' => $aportantes->gestion,
                                    'reintegro_normal' => $aportantes->reintegro_normal,
                                    'id_mes' => $aportantes->id_mes,
                                    'reintegro_incr_salarial' => $aportantes->reintegro_incr_salarial,
                                    'beneficio_social' => $aportantes->beneficio_social,
                                    'planilla_adicional' => $aportantes->planilla_adicional,
                                    'retroactivo' => $aportantes->retroactivo,
                                    'jubilado' => $aportantes->jubilado,
                                    'fecha_ingreso' => $aportantes->fecha_ingreso,
                                    'fecha_novedad_retiro_suspension' => $aportantes->fecha_novedad_retiro_suspension,
                                    'motivo_novedad_retiro_suspension' => $aportantes->motivo_novedad_retiro_suspension,
                                    'parentesco_rentista' => $aportantes->parentesco_rentista,
                                    'tipo_renta' => $aportantes->tipo_renta,
                                    'aporte_planilla' => $aportantes->aporte_planilla,
                                    'compensacion_salario_minimo' => $aportantes->compensacion_salario_minimo,
                                    'estado_compensacion' => $aportantes->estado_compensacion,
                                    'umsa_doc' => $aportantes->umsa_doc,
                                    'umsa_adm' => $aportantes->umsa_adm,
                                    'id_comp' => $aportantes->id_comp,
                                    'id_user_created' => auth()->user()->id,
                                ]);
                                AporteInstitucionalTemp::where('id', $aportantes->id)->update([
                                    'estado' => 1,
                                ]);

                                /*if($aportantes->fecha_novedad_retiro_suspension != null){
                                    $servicioBaja = new DarBajaCesante();
                                    $servicioBaja->bajaDefinitiva($aportantes->id_persona, $ComprobanteTemp->id_institucion,
                                        $aportantes->fecha_novedad_retiro_suspension, $aportantes->motivo_novedad_retiro_suspension, $aportantes->id_mes, $aportantes->gestion);
                                }*/
                            }
                        }

                        DB::commit();
                        $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionales');
                        $this->dispatch('SeValidoLaPlanilla');
                    } else {

                        DB::rollBack();
                        $this->dispatch('falta_planilla_umsa');
                    }
                }
            } else {

                //dd('ingresa');
                if ($ComprobanteTemp) {

                    $ComprobanteTemp->id_estado = 2;
                    $ComprobanteTemp->fecha_validado = Carbon::now();
                    $ComprobanteTemp->id_user_updated = auth()->user()->id;
                    $ComprobanteTemp->save();

                    // INICIO DE NOTIFICACION 
                    $institucion = $ComprobanteTemp->id_institucion;
                    $datosInstitucion = Institucion::find($institucion);
                    if ($datosInstitucion->id_usuario_responsable != null) {
                        $nuevaNotificacion = NotificacionUsuarios::create([
                            'estado' => 'NO LEIDO',
                            'prioridades' => 1,
                            'id_usu_emisor' => auth()->user()->id,
                            'id_usu_receptor' => $datosInstitucion->id_usuario_responsable,
                            'fecha_hora_enviada' => Carbon::now(),
                            'asunto' => 'APORTES - LA PLANILLA SE VALIDÓ CORRECTAMENTE',
                            'mensaje' => 'La planilla para su institución: ' . $datosInstitucion->nombre . ' fue validada correctamente.',
                        ]);
                    }
                    // FIN DE NOTIFICACION 

                    $AportantesTemp = AporteInstitucionalTemp::where('id_comp', $id_comprobante_validar)->get();

                    $claseAportante = Institucion::where('id', $ComprobanteTemp->id_institucion)
                        ->with('claseAportanteInstitucion')
                        ->first();
                }

                if ($claseAportante->claseAportanteInstitucion->nombre != 'RENTISTA') {


                    foreach ($AportantesTemp as $aportantes) {
                        $totalAporteSumado = $aportantes->total_aporte + $aportantes->compensacion_salario_minimo * 0.1;

                        $AportantesValidados = AporteInstitucional::create([
                            'id_persona' => $aportantes->id_persona,
                            'id_institucion' => $aportantes->id_institucion,
                            'item' => $aportantes->item,
                            'cargo' => $aportantes->cargo,
                            'area' => $aportantes->area,
                            'tipo_trabajador' => $aportantes->tipo_trabajador,
                            'carga_horaria' => $aportantes->carga_horaria,
                            'dias_trabajados' => $aportantes->dias_trabajados,
                            'total_ganado' => $aportantes->total_ganado,
                            'total_ganado_dia' => $aportantes->total_ganado_dia,
                            'total_aporte' => $totalAporteSumado,
                            'gestion' => $aportantes->gestion,
                            'reintegro_normal' => $aportantes->reintegro_normal,
                            'id_mes' => $aportantes->id_mes,
                            'reintegro_incr_salarial' => $aportantes->reintegro_incr_salarial,
                            'beneficio_social' => $aportantes->beneficio_social,
                            'planilla_adicional' => $aportantes->planilla_adicional,
                            'retroactivo' => $aportantes->retroactivo,
                            'jubilado' => $aportantes->jubilado,
                            'fecha_ingreso' => $aportantes->fecha_ingreso,
                            'fecha_novedad_retiro_suspension' => $aportantes->fecha_novedad_retiro_suspension,
                            'motivo_novedad_retiro_suspension' => $aportantes->motivo_novedad_retiro_suspension,
                            'parentesco_rentista' => $aportantes->parentesco_rentista,
                            'tipo_renta' => $aportantes->tipo_renta,
                            'aporte_planilla' => $aportantes->aporte_planilla,
                            'compensacion_salario_minimo' => $aportantes->compensacion_salario_minimo,
                            'estado_compensacion' => $aportantes->estado_compensacion,
                            'umsa_doc' => $aportantes->umsa_doc,
                            'umsa_adm' => $aportantes->umsa_adm,
                            'id_comp' => $aportantes->id_comp,
                            'id_user_created' => auth()->user()->id,
                        ]);
                        AporteInstitucionalTemp::where('id', $aportantes->id)->update([
                            'estado' => 1,
                        ]);

                        /*if($aportantes->fecha_novedad_retiro_suspension != null){
                            $servicioBaja = new DarBajaCesante();
                            $servicioBaja->bajaDefinitiva($aportantes->id_persona, $ComprobanteTemp->id_institucion,
                                $aportantes->fecha_novedad_retiro_suspension, $aportantes->motivo_novedad_retiro_suspension, $aportantes->id_mes, $aportantes->gestion);
                        }*/
                    }
                } else {
                    foreach ($AportantesTemp as $aportantes) {
                        $AportantesValidados = AporteInstitucional::create([
                            'id_persona' => $aportantes->id_persona,
                            'id_institucion' => $aportantes->id_institucion,
                            'item' => $aportantes->item,
                            'cargo' => $aportantes->cargo,
                            'area' => $aportantes->area,
                            'tipo_trabajador' => $aportantes->tipo_trabajador,
                            'carga_horaria' => $aportantes->carga_horaria,
                            'dias_trabajados' => $aportantes->dias_trabajados,
                            'total_ganado' => $aportantes->total_ganado,
                            'total_ganado_dia' => $aportantes->total_ganado_dia,
                            'total_aporte' => $aportantes->total_aporte,
                            'gestion' => $aportantes->gestion,
                            'reintegro_normal' => $aportantes->reintegro_normal,
                            'id_mes' => $aportantes->id_mes,
                            'reintegro_incr_salarial' => $aportantes->reintegro_incr_salarial,
                            'beneficio_social' => $aportantes->beneficio_social,
                            'planilla_adicional' => $aportantes->planilla_adicional,
                            'jubilado' => $aportantes->jubilado,
                            'fecha_ingreso' => $aportantes->fecha_ingreso,
                            'fecha_novedad_retiro_suspension' => $aportantes->fecha_novedad_retiro_suspension,
                            'motivo_novedad_retiro_suspension' => $aportantes->motivo_novedad_retiro_suspension,
                            'parentesco_rentista' => $aportantes->parentesco_rentista,
                            'tipo_renta' => $aportantes->tipo_renta,
                            'aporte_planilla' => $aportantes->aporte_planilla,
                            'compensacion_salario_minimo' => $aportantes->compensacion_salario_minimo,
                            'estado_compensacion' => $aportantes->estado_compensacion,
                            'umsa_doc' => $aportantes->umsa_doc,
                            'umsa_adm' => $aportantes->umsa_adm,
                            'id_comp' => $aportantes->id_comp,
                            'id_user_created' => auth()->user()->id,
                        ]);
                        AporteInstitucionalTemp::where('id', $aportantes->id)->update([
                            'estado' => 1,
                        ]);

                        /*if($aportantes->fecha_novedad_retiro_suspension != null){
                            $servicioBaja = new DarBajaCesante();
                            $servicioBaja->bajaDefinitiva($aportantes->id_persona, $ComprobanteTemp->id_institucion,
                                $aportantes->fecha_novedad_retiro_suspension, $aportantes->motivo_novedad_retiro_suspension, $aportantes->id_mes, $aportantes->gestion);
                        }*/
                    }
                }

                DB::commit();
                $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionales');
                $this->dispatch('SeValidoLaPlanilla');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('NoSeValidoLaPlanilla');

            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al validar planilla", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }


    // Funcion Validar UMSA DOCENTES 
    public function validar_umsa_docentes($id_comp)
    {

        DB::beginTransaction();
        try {

            $ComprobanteTemp = Comprobante::find($id_comp);
            if ($ComprobanteTemp) {
                $ComprobanteTemp->id_estado = 2;
                $ComprobanteTemp->fecha_validado = Carbon::now();
                $ComprobanteTemp->id_user_updated = auth()->user()->id;
                $ComprobanteTemp->save();

                // INICIO DE NOTIFICACION 
                $institucion = $ComprobanteTemp->id_institucion;
                $datosInstitucion = Institucion::find($institucion);
                if ($datosInstitucion->id_usuario_responsable != null) {
                    $nuevaNotificacion = NotificacionUsuarios::create([
                        'estado' => 'NO LEIDO',
                        'prioridades' => 1,
                        'id_usu_emisor' => auth()->user()->id,
                        'id_usu_receptor' => $datosInstitucion->id_usuario_responsable,
                        'fecha_hora_enviada' => Carbon::now(),
                        'asunto' => 'APORTES - LA PLANILLA SE VALIDÓ CORRECTAMENTE',
                        'mensaje' => 'La planilla para su institución: ' . $datosInstitucion->nombre . ' fue validada correctamente.',
                    ]);
                }
                // FIN DE NOTIFICACION 



                $AportantesTemp = AporteInstitucionalTemp::where('id_comp', $id_comp)->get();

                $claseAportante = Institucion::where('id', 2)
                    ->with('claseAportanteInstitucion')
                    ->first();

                if ($claseAportante->claseAportanteInstitucion->nombre != 'RENTISTA') {


                    foreach ($AportantesTemp as $aportantes) {
                        $totalAporteSumado = $aportantes->total_aporte + $aportantes->compensacion_salario_minimo * 0.1;

                        $AportantesValidados = AporteInstitucional::create([
                            'id_persona' => $aportantes->id_persona,
                            'id_institucion' => $aportantes->id_institucion,
                            'item' => $aportantes->item,
                            'cargo' => $aportantes->cargo,
                            'area' => $aportantes->area,
                            'tipo_trabajador' => $aportantes->tipo_trabajador,
                            'carga_horaria' => $aportantes->carga_horaria,
                            'dias_trabajados' => $aportantes->dias_trabajados,
                            'total_ganado' => $aportantes->total_ganado,
                            'total_ganado_dia' => $aportantes->total_ganado_dia,
                            'total_aporte' => $totalAporteSumado,
                            'gestion' => $aportantes->gestion,
                            'reintegro_normal' => $aportantes->reintegro_normal,
                            'id_mes' => $aportantes->id_mes,
                            'reintegro_incr_salarial' => $aportantes->reintegro_incr_salarial,
                            'beneficio_social' => $aportantes->beneficio_social,
                            'planilla_adicional' => $aportantes->planilla_adicional,
                            'retroactivo' => $aportantes->retroactivo,
                            'jubilado' => $aportantes->jubilado,
                            'fecha_ingreso' => $aportantes->fecha_ingreso,
                            'fecha_novedad_retiro_suspension' => $aportantes->fecha_novedad_retiro_suspension,
                            'motivo_novedad_retiro_suspension' => $aportantes->motivo_novedad_retiro_suspension,
                            'parentesco_rentista' => $aportantes->parentesco_rentista,
                            'tipo_renta' => $aportantes->tipo_renta,
                            'aporte_planilla' => $aportantes->aporte_planilla,
                            'compensacion_salario_minimo' => $aportantes->compensacion_salario_minimo,
                            'estado_compensacion' => $aportantes->estado_compensacion,
                            'umsa_doc' => $aportantes->umsa_doc,
                            'umsa_adm' => $aportantes->umsa_adm,
                            'id_comp' => $aportantes->id_comp,
                            'id_user_created' => auth()->user()->id,
                        ]);
                        AporteInstitucionalTemp::where('id', $aportantes->id)->update([
                            'estado' => 1,
                        ]);
                    }
                }

                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('NoSeValidoLaPlanilla');
        }
    }

    // Funcion Validar UMSA ADMINISTRACION
    public function validar_umsa_administrativos($id_comp)
    {
        //dd(2655786);

        DB::beginTransaction();
        try {

            $ComprobanteTemp = Comprobante::find($id_comp);
            if ($ComprobanteTemp) {
                $ComprobanteTemp->id_estado = 2;
                $ComprobanteTemp->fecha_validado = Carbon::now();
                $ComprobanteTemp->id_user_updated = auth()->user()->id;
                $ComprobanteTemp->save();

                // INICIO DE NOTIFICACION 
                $institucion = $ComprobanteTemp->id_institucion;
                $datosInstitucion = Institucion::find($institucion);
                if ($datosInstitucion->id_usuario_responsable != null) {
                    $nuevaNotificacion = NotificacionUsuarios::create([
                        'estado' => 'NO LEIDO',
                        'prioridades' => 1,
                        'id_usu_emisor' => auth()->user()->id,
                        'id_usu_receptor' => $datosInstitucion->id_usuario_responsable,
                        'fecha_hora_enviada' => Carbon::now(),
                        'asunto' => 'APORTES - LA PLANILLA SE VALIDÓ CORRECTAMENTE',
                        'mensaje' => 'La planilla para su institución: ' . $datosInstitucion->nombre . ' fue validada correctamente.',
                    ]);
                }
                // FIN DE NOTIFICACION 
            }

            $AportantesTemp = AporteInstitucionalTemp::where('id_comp', $id_comp)->get();

            $claseAportante = Institucion::where('id', 6)
                ->with('claseAportanteInstitucion')
                ->first();

            if ($claseAportante->claseAportanteInstitucion->nombre != 'RENTISTA') {
                foreach ($AportantesTemp as $aportantes) {
                    $totalAporteSumado = $aportantes->total_aporte + $aportantes->compensacion_salario_minimo * 0.1;

                    $AportantesValidados = AporteInstitucional::create([
                        'id_persona' => $aportantes->id_persona,
                        'id_institucion' => $aportantes->id_institucion,
                        'item' => $aportantes->item,
                        'cargo' => $aportantes->cargo,
                        'area' => $aportantes->area,
                        'tipo_trabajador' => $aportantes->tipo_trabajador,
                        'carga_horaria' => $aportantes->carga_horaria,
                        'dias_trabajados' => $aportantes->dias_trabajados,
                        'total_ganado' => $aportantes->total_ganado,
                        'total_ganado_dia' => $aportantes->total_ganado_dia,
                        'total_aporte' => $totalAporteSumado,
                        'gestion' => $aportantes->gestion,
                        'reintegro_normal' => $aportantes->reintegro_normal,
                        'id_mes' => $aportantes->id_mes,
                        'reintegro_incr_salarial' => $aportantes->reintegro_incr_salarial,
                        'beneficio_social' => $aportantes->beneficio_social,
                        'planilla_adicional' => $aportantes->planilla_adicional,
                        'retroactivo' => $aportantes->retroactivo,
                        'jubilado' => $aportantes->jubilado,
                        'fecha_ingreso' => $aportantes->fecha_ingreso,
                        'fecha_novedad_retiro_suspension' => $aportantes->fecha_novedad_retiro_suspension,
                        'motivo_novedad_retiro_suspension' => $aportantes->motivo_novedad_retiro_suspension,
                        'parentesco_rentista' => $aportantes->parentesco_rentista,
                        'tipo_renta' => $aportantes->tipo_renta,
                        'aporte_planilla' => $aportantes->aporte_planilla,
                        'compensacion_salario_minimo' => $aportantes->compensacion_salario_minimo,
                        'estado_compensacion' => $aportantes->estado_compensacion,
                        'umsa_doc' => $aportantes->umsa_doc,
                        'umsa_adm' => $aportantes->umsa_adm,
                        'id_comp' => $aportantes->id_comp,
                        'id_user_created' => auth()->user()->id,
                    ]);
                    AporteInstitucionalTemp::where('id', $aportantes->id)->update([
                        'estado' => 1,
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('NoSeValidoLaPlanilla');
        }
    }


    #[On('ConfirmarRegistroBajaMedica')]
    public function ConfirmarBajaMedica($data)
    {
        try {
            $compId_validar = $data['comprobanteId'];

            $this->dispatch('ConfirmarBajaMedicaSw', id_comprobante: $compId_validar);
        } catch (\Throwable $th) {
            $this->dispatch('error');
        }
    }

    #[On('RegistroBajaMedica')]
    public function RegistrarBajasMedicas($data)
    {
        DB::beginTransaction();
        try {
            $datos_comprobante = Comprobante::find($data);

            $datos_bajas = PlanillaBajaMedica::join('aportes.aporte_i as api', 'api.id', '=', 'aportes.planilla_baja_medica.id_aporte_i')
                ->where('api.id_mes', '=', $datos_comprobante->id_mes)
                ->where('api.gestion', '=', $datos_comprobante->gestion)
                ->where('api.id_institucion', '=', $datos_comprobante->id_institucion)
                ->get();
            $total_monto_baja_medica = $datos_bajas->sum('monto_baja_medica');
            $total_con_descuentos = $datos_comprobante->monto_total_comprobante - $total_monto_baja_medica;
            if ($datos_comprobante) {
                $datos_comprobante->monto_bajas_medicas = $total_monto_baja_medica;
                $datos_comprobante->total_comprobante = $total_con_descuentos;
                $datos_comprobante->fecha_liquidacion_baja_medica = Carbon::now();
                $datos_comprobante->save();
            }
            $inst = Institucion::find($datos_comprobante->id_institucion);
            $mes = Mes::find($datos_comprobante->id_mes);

            if ($inst->id_usuario_responsable != null) {
                $nuevaNotificacion = NotificacionUsuarios::create([
                    'estado' => 'NO LEIDO',
                    'prioridades' => 1,
                    'id_usu_emisor' => auth()->user()->id,
                    'id_usu_receptor' => $inst->id_usuario_responsable,
                    'fecha_hora_enviada' => Carbon::now(),
                    'asunto' => 'APORTES - LAS BAJAS MÉDICAS SE VALIDARON CORRECTAMENTE',
                    'mensaje' => 'Las bajas médicas pertenecientes a la planilla correspondiente al mes de ' . $mes->mes . ' de ' . $datos_comprobante->gestion . ' fueron validadas correctamente por un total de ' . $total_monto_baja_medica . ' Bs.',
                ]);
            }
            DB::commit();

            $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionales');
            $this->dispatch('DescargaExitosaExtracto');
            //return $this->redirectRoute('aportes.bajasmedicas.pdf', ['id' => $data, ]) ;
            // $this->js('window.open("aportes/BajasMedicas/Pdf/' . $data . '", "_blank")');
            $this->js('window.open("' . route('aportes.bajasmedicas.pdf', ['id' => $data]) . '", "_blank")');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error');
        }
    }

    // para VER EL PDF
    #[On('verPdf')]
    public function verPdf($data)
    {
        $this->compId = $data;
        $this->dispatch('alertaDescargarPdf', ['id_comprobante' => $this->compId]);
    }

    public function descargarPdf($data)
    {
        $this->js('window.open("/aportes/pdf/' . $this->compId . '", "_blank")');
    }

    // para VER LA COPIA DEL  PDF
    #[On('verCopiaPdf')]
    public function verCopiaPdf($data)
    {
        $this->compId = $data;
        $this->dispatch('alertaDescargarCopiaPdf', ['id_comprobante' => $this->compId]);
    }

    public function descargarCopiaPdf($data)
    {
        $this->js('window.open("/aportes/pdfcopia/' . $this->compId . '", "_blank")');
    }

    // para VER LA COPIA DE BAJA MEDICA PDF
    #[On('funcion_pdf_baja_medica')]
    public function funcion_pdf_baja_medica($data)
    {
        $this->mostrarArchivoPdfBajaMedica($data);
        $this->dispatch('modalVerPdfBajaMedica');
    }
    public $pdf_vista;
    public function mostrarArchivoPdfBajaMedica($id)
    {
        try {
            if ($id) {

                //$path = 'Aportes/Certificados/' . $id . '.pdf';
                $path = 'Aportes/Liquidaciones_Bajas_Medicas/' .  $id . '.pdf';
                //dd($this->idTit); // Adjust path as needed
                $fileContents = Storage::disk('ftp')->get($path);
                $pdfBase64 = base64_encode($fileContents);
                //dd($pdfBase64);
                $this->pdf_vista = $pdfBase64;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }



    // Funcion pre liquidacion
    #[On('funcion_preliquidacion')]
    public function funcion_preliquidacion($data)
    {

        $this->compId = $data;
        $this->mostrarComprobante($this->compId);
        $this->dispatch('modalPreLiquidacion');
    }

    // Funcion ver aportantes
    #[On('funcion_veraportantes')]
    public function funcion_veraportantes($data)
    {
        $this->compId = $data;
        $this->dispatch('modalListaAportantes');
    }


    // Funcion forma pago con ruta
    #[On('funcion_formapago_vista')]
    public function funcion_formapago_vista($data)
    {
        return redirect()->route('aportes.aporte_institucional.forma_pago', ['id' => $data]);
    }

    // Funcion forma  pago
    #[On('funcion_formapago')]
    public function funcion_formapago($data)
    {
        $this->mostrarComprobante($data);

        $this->monto_excedente = 0;
        $this->monto_deduccion_r = 0;
        $this->compId = $data;
        $datos_compro_ant = Comprobante::where('id_institucion', '=', $this->compId)
            ->where('id_estado', '=', 4)
            ->orderby('id', 'desc')
            ->first();
        if ($datos_compro_ant != null) {
            $this->nro_compro_anterior = $datos_compro_ant->nro_registro_comprobante;
            $this->total_compro_anterior = $datos_compro_ant->total_comprobante;
        } else {
            $this->nro_compro_anterior = 0;
            $this->total_compro_anterior = 0;
        }


        $this->mostrarFormaPago($data);

        $this->dispatch('modalFormaPago');
    }
    // Aqui se muestra la forma de pago anterior para la vista Forma de Pago
    #[On('formasPago')]
    public function mostrarFormaPago($data)
    {

        $comprobante = Comprobante::find($data);
        $datos_compro_ant = Comprobante::where('id_institucion', '=', $comprobante->id_institucion)
            ->where('id_estado', '=', 4)
            ->orderby('id', 'desc')
            ->first();

        if ($datos_compro_ant != null) {
            $this->nro_compro_anterior = $datos_compro_ant->nro_registro_comprobante;
            $this->nro_compro_anterior_m = $datos_compro_ant->nro_registro_comprobante . '/' . $datos_compro_ant->gestion;
            $this->total_compro_anterior = $datos_compro_ant->total_comprobante;
            $this->fecha_liquidado_ant = Carbon::parse($datos_compro_ant->fecha_liquidacion)->format('d/m/Y');
            $this->beneficio_social_ant = $datos_compro_ant->beneficio_social;
            $this->planilla_adicional_ant = $datos_compro_ant->planilla_adicional;
            $this->reintegro_ant = $datos_compro_ant->reintegro;
        } else {
            $this->nro_compro_anterior = 0;
            $this->total_compro_anterior = 0;
            $this->fecha_liquidado = '';
            $this->beneficio_social = '';
            $this->planilla_adicional = '';
            $this->reintegro = '';
        }
    }
    // Funcion liquidacion
    #[On('funcion_liquidacion')]
    public function funcion_liquidacion($data)
    {
        $this->reset();
        $this->mostrarComprobante($data);
        $this->mostrarPdfFilePago($data);
        $this->compId = $data;
        $this->reset('dato_fecha_liquidacion', 'observacion');

        $this->dispatch('modalLiquidacion');
    }



    public function actualizar_planillas() {}
    public function abrir_file_pago()
    {
        $this->dispatch('modalVerPdFormaPago');
        $this->skipRender();
    }
    public $pdf_forma_pago;
    public function mostrarPdfFilePago($id_comp)
    {
        try {
            if ($id_comp) {
                $forma = FormaPago::where('id_comprobante', $id_comp)->first();
                if ($forma != null) {
                    $id_forma = $forma->id;
                    // dd($forma);
                    $path = 'Aportes/FormaPago/' . 'FP_' . $id_forma . '.pdf';
                    $fileContents = Storage::disk('ftp')->get($path);
                    $pdfBase64 = base64_encode($fileContents);

                    $this->pdf_forma_pago = $pdfBase64;
                }
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    #[On('abrir_vista_preliquidacion')]
    public function abrir_vista_preliquidacion($data)
    {
        $idComp = $data['comprobanteId'];
        return redirect()->route('aportes.aporte_institucional.preliquidacion', ['id' => $idComp]);
    }

    public $nro_pago, $salario_cotizable_m, $aporte_a_liquidar, $total_compensacion, $valor_tgn, $forma_pago_c_m, $total_a_pagar_m;
    public function mostrarComprobante($compId)
    {

        if ($compId) {
            $this->id_comp = $compId;
            $comprobante = Comprobante::find($compId);

            if ($comprobante) {

                $this->id_institucion = $comprobante->id_institucion;

                $this->id_mes = $comprobante->id_mes;
                $this->gestion = $comprobante->gestion;
                $this->salario_cotizable = $comprobante->total_salario_cotizable;
                $this->salario_cotizable_m = number_format($comprobante->total_salario_cotizable, 2, ',', '.');

                $this->beneficio_social = $comprobante->beneficio_social;
                $this->planilla_adicional = $comprobante->planilla_adicional;
                $this->reintegro = $comprobante->reintegro;
                $this->retroactivo = $comprobante->retroactivo;
                $this->monto_deduccion_r = $comprobante->monto_deduccion;
                $this->monto_excedente = $comprobante->monto_demasia;

                $this->deduccion_descripcion = $comprobante->deduccion_descripcion;
                $this->demasia_descripcion = $comprobante->demasia_descripcion;

                $this->fecha_liquidado = $comprobante->fecha_liquidacion;
                $id_estado = $comprobante->id_estado;
                $this->total_compensacion = $comprobante->total_compensacion;


                $user_created = $comprobante->id_user_created;
                $this->fecha_user_created = $comprobante->created_at;

                $user_updated = $comprobante->id_user_updated;
                $this->fecha_user_updated = $comprobante->updated_at;

                $this->importe_aporte_patronal = $comprobante->importe_aporte_patronal;
                $this->total_a_pagar_m = $this->total_compensacion + $this->importe_aporte_patronal;
            }


            $datosrepresentanteLegal = Institucion::join('administracion.persona as per', 'per.id', '=', 'aportes.institucion.id_persona')
                ->where('aportes.institucion.id', '=', $comprobante->id_institucion)
                ->first();

            $institucionActual = Institucion::find($comprobante->id_institucion);
            if ($institucionActual) {
                $this->nombre_institucion = $institucionActual->nombre;
                $this->nit = $institucionActual->nit;
                $this->tgn = $institucionActual->tgn;
            }

            $claseActual = ClaseAportante::find($institucionActual->id_clase_aportante);
            if ($claseActual) {
                $this->nombre_clase = $claseActual->nombre;
                $this->tipo_clase = $claseActual->tipo;
                $this->tasa_clase = $claseActual->porcentaje;
            }
            $mesActual = Mes::find($comprobante->id_mes);
            if ($mesActual) {
                $this->nombre_mes = $mesActual->mes;
                $this->numero_mes = $mesActual->numero;
            }
            // monto de importe
            $this->importe = number_format(($comprobante->importe_aporte_patronal), 2, ',', '.');


            if ($this->tgn == true) {
                $this->valor_tgn = number_format($comprobante->importe_aporte_patronal * 0.05, 2, ',', '.');
                // monto a liquidar 
                $this->aporte_a_liquidar = number_format(($comprobante->importe_aporte_patronal) + ($comprobante->total_compensacion) - ($comprobante->importe_aporte_patronal * 0.05), 2, ',', '.');
            } elseif ($this->tgn == false) {
                $this->valor_tgn = 0;
                // monto a liquidar 
                $this->aporte_a_liquidar = number_format(($comprobante->importe_aporte_patronal) + ($comprobante->total_compensacion), 2, ',', '.');
            }

            $estadoActual = Estado::find($id_estado);
            $this->estado_comprobante = $estadoActual->descripcion;

            $userCreated = User::find($user_created);
            $this->user_created_name = $userCreated?->name ?? '';

            $userUpdated = User::find($user_updated);
            $this->user_updated_name = $userUpdated?->name ?? '';

            $this->anio_sistema = Carbon::now()->year;
            $this->m_nro_registro = $this->obtenerUltimoNroComprobante() . ' / ' . $this->anio_sistema;

            $formaActual = FormaPago::where('id_comprobante', $compId)->first();
            if ($formaActual) {
                $this->forma_pago_c = $formaActual->total_forma_pago;
                $this->forma_pago_c_m = number_format($formaActual->total_forma_pago, 2, ',', '.');
                $this->fecha_pago = $formaActual->fecha_pago;
                $this->nro_pago = $formaActual->nro_pago;
                $this->tipo_pago = $formaActual->tipo_pago;
            } else {
                $this->forma_pago_c = 0;
            }
            return $comprobante;
        }
    }

    // Excel de la planilla en PDF
    #[On('excelPdf')]
    public function excelPdf($data)
    {

        try {
            $this->js('window.open("' . route('pdf.planillaGenerate', ['rowId' => $data]) . '", "_blank")');
        } catch (\Throwable $th) {
            return null;
        }
    }
}
