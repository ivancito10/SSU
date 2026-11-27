<?php

namespace App\Livewire\Aportes\Externos\Aporte;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Imports\YourImportClass;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Aportes\AportesPlanillasImport;
use App\Imports\Aportes\ProbandoImports;
use App\Models\Administracion\Persona;
use App\Models\Aportes\Institucion;
use App\Models\Aportes\AporteInstitucionalTemp;
use App\Services\ValidarPersona;
use App\Models\Aportes\Mes;
use Illuminate\Support\Facades\DB;
use App\Models\Aportes\Comprobante;
use App\Services\ConexionAsuss;
use Illuminate\Support\Facades\Storage;
use App\Mail\Aportes\PlanillasInstitucionalesMailable;
use Illuminate\Support\Facades\Mail;
use App\Models\Administracion\Role;
use App\Models\Administracion\RolUsuario;
use App\Models\Administracion\NotificacionUsuarios;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use Livewire\Attributes\Validate;

class AporteInstitucionRegisterExt extends Component
{
    use WithFileUploads;
    #[Validate]
    public $gestion, $planilla, $id_institucion, $id_mes, $id_tipo_aporte;

    public $esperandoDatos = false;

    public $count;
    public $reintegro;
    public $beneficio;
    public $planilla_adicional;
    public $retroactivo;
    public $instituciones = [], $mes = [];
    public $tipoError;
    public $umsa_state;
    public $lote_comprobante;
    public $lote_comp_umsa;

    protected $listeners = ['SubirPlanilla'];

    public function render()
    {
        return view('livewire.Aportes.Externos.aporte.aporte-institucion-register-ext');
    }
    public function mount()
    {
        $this->instituciones = Institucion::where('estado_validado', '=', true)
            ->where('id_usuario_responsable', '=', auth()->user()->id)
            ->get();
        $this->mes = Mes::get();
        $this->gestion = Carbon::now()->year;
    }
    public function rules()
    {
        return [
            'id_institucion' => 'required',
            'id_tipo_aporte' => 'required',
            'id_mes' => 'required',
            'gestion' => 'required',
            'planilla' => 'required|file|mimes:xls,xlsx',

        ];
    }
    public function updatedPlanilla()
    {
        $this->validateOnly('planilla');
    }

    public function SubirPlanilla()
    { 
        // LIZ:  ESTAS SON MIS VALIDACIONES , SI CAUSA ERRORES LO COMENTAN :( 
        //  if (!$this->id_institucion) {
        //      $this->tipoError   continuaraa 11/02/2025
        //  }
        //  dd($this->id_institucion, $this->id_tipo_aporte, $this->id_mes, $this->gestion, $this->planilla);
        // FIN DE MIS VALIDACIONES -------------------- ya no termine :( 
        // $respuesta = new ValidarPersona();
        // $dato = $respuesta->buscarPersona('2642714',null);
        // dd($dato);
        set_time_limit(12000);
        /*$respuesta_con = new ValidarPersona();
        $dato_con = $respuesta_con->buscarPersona('10911813', null);
        if (isset($dato_con['error'])) {
            $conexionAsuss_con = new ConexionAsuss();
            $logeo_con = $conexionAsuss_con->login();
        }*/
        // if ($this->gestion== null || $this->planilla== null) {
        //     $this->dispatch('falta_datos');
        // }

        
        $this->validate();

        DB::beginTransaction();

        try {
            

            $this->esperandoDatos = true;
            $file = $this->planilla;
            if ($this->id_tipo_aporte == '0') {
                $this->reintegro = 0;
                $this->beneficio = 0;
                $this->planilla_adicional = 0;
                $this->retroactivo = 0;
            }
            if ($this->id_tipo_aporte == '1') {
                $this->reintegro = 1;
                $this->beneficio = 0;
                $this->planilla_adicional = 0;
                $this->retroactivo = 0;
            }
            if ($this->id_tipo_aporte == '2') {
                $this->reintegro = 0;
                $this->beneficio = 1;
                $this->planilla_adicional = 0;
                $this->retroactivo = 0;
            }
            if ($this->id_tipo_aporte == '3') {
                $this->reintegro = 0;
                $this->beneficio = 0;
                $this->planilla_adicional = 1;
                $this->retroactivo = 0;
            }
            if ($this->id_tipo_aporte == '4') {
                $this->reintegro = 0;
                $this->beneficio = 0;
                $this->planilla_adicional = 0;
                $this->retroactivo = 1;
            }

             

            $porcentaje = Institucion::where('id', $this->id_institucion)->with('claseAportanteInstitucion')->first();
            $valor_porcentaje = $porcentaje->claseAportanteInstitucion->porcentaje;


            if($this->id_tipo_aporte == '0' || $this->id_tipo_aporte == '4'){
                    $comprobar_comprobante = Comprobante::where('id_institucion', '=', $this->id_institucion)
                    ->where('id_mes', '=', $this->id_mes)
                    ->where('gestion', '=', $this->gestion)
                    ->where('reintegro', '=', $this->reintegro)
                    ->where('beneficio_social', '=', $this->beneficio)
                    ->where('planilla_adicional', '=', $this->planilla_adicional)
                    ->where('retroactivo', '=', $this->retroactivo)
                    ->first();

                    if ($comprobar_comprobante == null) {

                    /*$import = new AportesPlanillasImport($this->id_institucion, $this->id_mes, $this->id_tipo_aporte, $this->gestion, $porcentaje->claseAportanteInstitucion->porcentaje);
                    $probando = Excel::import($import, $file, \Maatwebsite\Excel\Excel::XLSX);   //DENME CHANCEEEEE
                    //Excel::import(new ProbandoImports, $file);*/

                    // Aqui creamos un nuevo lote_comprobante unico de 8 digitos
                    $timestamp_short = substr(time(), -6); // Obtener los últimos 6 dígitos del timestamp
                    $random_part = Str::random(2); // Generar 2 caracteres aleatorios
                    $this->lote_comprobante = $timestamp_short . $random_part;

                    
                    if($this->id_institucion == 2){
                        $this->umsa_state = 1;
                    }elseif($this->id_institucion == 6){
                        $this->umsa_state = 2;
                    }else{
                        $this->umsa_state = 3;
                    }
    
                    try {
                        
                        Excel::import(new ProbandoImports(
                            $this->id_institucion,
                            $this->id_mes,
                            $this->id_tipo_aporte,
                            $this->gestion,
                            $porcentaje->claseAportanteInstitucion->porcentaje,
                            $this->umsa_state, $this->lote_comprobante), $file);
                    } catch (\Exception $e) {
                        //dd($e);
                        $this->dispatch('errorImportacion', mensaje: $e->getMessage());
                        return;
                    }

                    $datos_planilla = AporteInstitucionalTemp::where('lote_comp', '=', $this->lote_comprobante)->get();

                    $permanentes = $datos_planilla->where('tipo_trabajador', 'PERMANENTE')->count();
                    $eventuales = $datos_planilla->where('tipo_trabajador', 'EVENTUAL')->count();
                    $total_ganado_sum = $datos_planilla->sum('total_ganado');
                    $total_compensacion_sum = $datos_planilla->sum('compensacion_salario_minimo');
                    $total_aporte_sum = $datos_planilla->sum('total_aporte');

                    $comprobante = Comprobante::create([
                        'id_institucion' => $this->id_institucion,
                        'gestion' => $this->gestion,
                        'id_estado' => 1,
                        'cantidad_eventuales' => $eventuales,
                        'cantidad_permanentes' => $permanentes,
                        'total_salario_cotizable' => $total_ganado_sum,
                        'importe_aporte_patronal' => $total_aporte_sum,
                        'reintegro' => $this->reintegro,
                        'beneficio_social' => $this->beneficio,
                        'planilla_adicional' => $this->planilla_adicional,
                        'retroactivo' => $this->retroactivo,
                        'id_mes' => $this->id_mes,
                        'total_compensacion' => $total_compensacion_sum,
                        'id_user_created' => auth()->user()->id,
                    ]);
                    $id_comp = $comprobante->id;

                    // Actualizamos el aporte_i_temp con id_comprobante  
                    AporteInstitucionalTemp::where('lote_comp', $this->lote_comprobante)
                    ->update(['id_comp' => $id_comp]);

                    // Actualizamos el lote_comp a null ya que tenemos el id_comp 
                    AporteInstitucionalTemp::where('id_comp', $id_comp)
                    ->update(['lote_comp' => null]);


                    // Aqui la lógica para crear un 3er comprobante UMSA general
                     
                        // UMSA DOCENTES
                        if($this->id_institucion == 2){

                            $result = $this->buscar_comp_umsa($this->id_mes, $this->gestion, $this->reintegro, $this->beneficio, $this->planilla_adicional, $this->retroactivo);
        
                            if($result == null){ 
                                
                                $this->crear_ap_insti_temp_umsa($this->id_mes, $this->gestion, $this->id_tipo_aporte, $valor_porcentaje, 1);
                                
                                $nro_lote = $this->lote_comp_umsa;
                                $this->crear_comp_umsa($this->gestion, $eventuales, $permanentes, $total_ganado_sum, $total_aporte_sum, 
                                                        $this->reintegro, $this->beneficio, $this->planilla_adicional, $this->retroactivo, 
                                                        $this->id_mes, $total_compensacion_sum, $nro_lote);
                                

                            }elseif($result != null){ 
                                
                                $id = $result->id;
                                $a_eventuales = $result->cantidad_eventuales + $eventuales;
                                $a_permanentes = $result->cantidad_permanentes + $permanentes;
                                $a_total_salario_cotizable = $result->total_salario_cotizable + $total_ganado_sum;
                                $a_importe = $result->importe_aporte_patronal + $total_aporte_sum;
                                $a_total_compensacion =$result->total_compensacion + $total_compensacion_sum;
                                $a_id_user_updated = auth()->user()->id;

                                $this->crear_ap_insti_temp_umsa($this->id_mes, $this->gestion, $this->id_tipo_aporte, $valor_porcentaje, 1);

                                $nro_lote = $this->lote_comp_umsa;
                                $this->act_comp_umsa($id, $a_eventuales, $a_permanentes, $a_total_salario_cotizable,
                                                    $a_importe,$a_total_compensacion, $a_id_user_updated, $nro_lote );
                            }
                        // UMSA ADMINISTRATIVOS
                        }elseif($this->id_institucion == 6){
                            $result = $this->buscar_comp_umsa($this->id_mes, $this->gestion, $this->reintegro, $this->beneficio, $this->planilla_adicional, $this->retroactivo);
        
                            if($result == null){

                                $this->crear_ap_insti_temp_umsa($this->id_mes, $this->gestion, $this->id_tipo_aporte, $valor_porcentaje, 2);

                                $nro_lote = $this->lote_comp_umsa;
                                $this->crear_comp_umsa($this->gestion, $eventuales, $permanentes, $total_ganado_sum, $total_aporte_sum, 
                                                        $this->reintegro, $this->beneficio, $this->planilla_adicional,
                                                        $this->retroactivo, $this->id_mes, $total_compensacion_sum, $nro_lote);

                            }elseif($result != null){ 
                                $id = $result->id;
                                $a_eventuales = $result->cantidad_eventuales + $eventuales;
                                $a_permanentes = $result->cantidad_permanentes + $permanentes;
                                $a_total_salario_cotizable = $result->total_salario_cotizable + $total_ganado_sum;
                                $a_importe = $result->importe_aporte_patronal + $total_aporte_sum;
                                $a_total_compensacion =$result->total_compensacion + $total_compensacion_sum;
                                $a_id_user_updated = auth()->user()->id;

                                $this->crear_ap_insti_temp_umsa($this->id_mes, $this->gestion, $this->id_tipo_aporte, $valor_porcentaje,2);

                                $nro_lote = $this->lote_comp_umsa;
                                $this->act_comp_umsa($id, $a_eventuales, $a_permanentes, $a_total_salario_cotizable,
                                                    $a_importe,$a_total_compensacion, $a_id_user_updated , $nro_lote);
                            }
                            
                        }
                     
                    // Fin umsa

                    
                    if($comprobante !=null){
                        // INICIO DE NOTIFICACION
                        $rol = Role::where('name', 'Encargado Aportes')->first();
                        $receptores = RolUsuario::where('role_id', $rol->id)->get();
                        $institucion = Institucion::find($this->id_institucion);
                        $nomInstitucion = $institucion->nombre;
                        $mesId = Mes::find($this->id_mes);
                        $nombreMes = $mesId->mes;
                        if ($this->id_tipo_aporte == 0) {
                            $tipoAporte = 'PATRONAL';
                        } elseif ($this->id_tipo_aporte == 1) {
                            $tipoAporte = 'REINTEGRO';
                        } elseif ($this->id_tipo_aporte == 2) {
                            $tipoAporte = 'BENEFICIO SOCIAL';
                        } elseif ($this->id_tipo_aporte == 3) {
                            $tipoAporte = 'PLANILLA ADICIONAL';
                        } 
                            elseif ($this->id_tipo_aporte == 4) {
                            $tipoAporte = 'RETROACTIVO';
                        }
                        

                        if ($rol) {
                            $receptores = RolUsuario::where('role_id', $rol->id)->get();
                            foreach ($receptores as $receptor) {
                                $nuevaNotificacion = NotificacionUsuarios::create([
                                    'estado' => 'NO LEIDO',
                                    'prioridades' => 1,
                                    'id_usu_emisor' => auth()->user()->id,
                                    'id_usu_receptor' => $receptor->model_id,
                                    'fecha_hora_enviada' => Carbon::now(),
                                    'asunto' => 'APORTES - PLANILLA REGISTRADA',
                                    'mensaje' => 'La institución ' . $nomInstitucion . ' subió planilla del mes de: ' . $nombreMes . ' , ' . $this->gestion . ' .De tipo: ' . $tipoAporte,
                                ]);
                            }
                        }
                        // FIN DE NOTIFICACION 
                    } 

                    $correo_ins = Institucion::where('id', '=', $this->id_institucion)->first();

                    //ftp
                    $customFileName = $correo_ins->nombre . '-' . $this->id_mes . '-' . $this->id_tipo_aporte . '-' . $this->gestion . '.' . $file->getClientOriginalExtension();
                    $filePath = 'Aportes/Planillas/' . $customFileName;
                    $fileContents = file_get_contents($file->getRealPath());
                    Storage::disk('ftp')->put($filePath, $fileContents);

                    //mail
                    $asunto = 'Sistema Sighov2 - Unidad de Aportes';
                    $email = $correo_ins->correo;
                    $correo = new PlanillasInstitucionalesMailable($asunto, $correo_ins, $this->id_mes, $this->gestion, $this->id_tipo_aporte);
                    try {
                        Mail::to($email)->send($correo);
                    } catch (\Exception $e) {
                        // Log del error (opcional)
                        Log::error('No se pudo enviar el correo: ' . $e->getMessage());
                    }

                    $this->reset(['id_institucion', 'id_mes', 'id_tipo_aporte', 'gestion', 'planilla']);
                    $this->esperandoDatos = false;
                    $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionalesExterno');

                    // throw new \Exception('Error forzado despues de la importacion.');
                    DB::commit();
                    $this->dispatch('SubirPlanillaExitoso');
                    
                } else {
                    DB::rollBack();
                    $this->dispatch('ErrorYaExistePlanilla');
                    throw new \Exception('Ya existe una planilla con estas características.');
                }


            }else{

                    /*$import = new AportesPlanillasImport($this->id_institucion, $this->id_mes, $this->id_tipo_aporte, $this->gestion, $porcentaje->claseAportanteInstitucion->porcentaje);
                    $probando = Excel::import($import, $file, \Maatwebsite\Excel\Excel::XLSX);   //DENME CHANCEEEEE
                    //Excel::import(new ProbandoImports, $file);*/

                    // Aqui creamos un nuevo lote_comprobante unico de 8 digitos
                    $timestamp_short = substr(time(), -6); // Obtener los últimos 6 dígitos del timestamp
                    $random_part = Str::random(2); // Generar 2 caracteres aleatorios
                    $this->lote_comprobante = $timestamp_short . $random_part;

                    
                    if($this->id_institucion == 2){
                        $this->umsa_state = 1;
                    }elseif($this->id_institucion == 6){
                        $this->umsa_state = 2;
                    }else{
                        $this->umsa_state = 3;
                    }
    
                    try {
                        
                        Excel::import(new ProbandoImports(
                            $this->id_institucion,
                            $this->id_mes,
                            $this->id_tipo_aporte,
                            $this->gestion,
                            $porcentaje->claseAportanteInstitucion->porcentaje,
                            $this->umsa_state, $this->lote_comprobante), $file);
                    } catch (\Exception $e) {
                        //dd($e);
                        $this->dispatch('errorImportacion', mensaje: $e->getMessage());
                        return;
                    }

                    $datos_planilla = AporteInstitucionalTemp::where('lote_comp', '=', $this->lote_comprobante)->get();

                    $permanentes = $datos_planilla->where('tipo_trabajador', 'PERMANENTE')->count();
                    $eventuales = $datos_planilla->where('tipo_trabajador', 'EVENTUAL')->count();
                    $total_ganado_sum = $datos_planilla->sum('total_ganado');
                    $total_compensacion_sum = $datos_planilla->sum('compensacion_salario_minimo');
                    $total_aporte_sum = $datos_planilla->sum('total_aporte');

                    $comprobante = Comprobante::create([
                        'id_institucion' => $this->id_institucion,
                        'gestion' => $this->gestion,
                        'id_estado' => 1,
                        'cantidad_eventuales' => $eventuales,
                        'cantidad_permanentes' => $permanentes,
                        'total_salario_cotizable' => $total_ganado_sum,
                        'importe_aporte_patronal' => $total_aporte_sum,
                        'reintegro' => $this->reintegro,
                        'beneficio_social' => $this->beneficio,
                        'planilla_adicional' => $this->planilla_adicional,
                        'retroactivo' => $this->retroactivo,
                        'id_mes' => $this->id_mes,
                        'total_compensacion' => $total_compensacion_sum,
                        'id_user_created' => auth()->user()->id,
                    ]);
                    $id_comp = $comprobante->id;

                    // Actualizamos el aporte_i_temp con id_comprobante  
                    AporteInstitucionalTemp::where('lote_comp', $this->lote_comprobante)
                    ->update(['id_comp' => $id_comp]);

                    // Actualizamos el lote_comp a null ya que tenemos el id_comp 
                    AporteInstitucionalTemp::where('id_comp', $id_comp)
                    ->update(['lote_comp' => null]);

                    
                    
                    if($comprobante !=null){
                        // INICIO DE NOTIFICACION
                        $rol = Role::where('name', 'Encargado Aportes')->first();
                        $receptores = RolUsuario::where('role_id', $rol->id)->get();
                        $institucion = Institucion::find($this->id_institucion);
                        $nomInstitucion = $institucion->nombre;
                        $mesId = Mes::find($this->id_mes);
                        $nombreMes = $mesId->mes;
                        if ($this->id_tipo_aporte == 0) {
                            $tipoAporte = 'PATRONAL';
                        } elseif ($this->id_tipo_aporte == 1) {
                            $tipoAporte = 'REINTEGRO';
                        } elseif ($this->id_tipo_aporte == 2) {
                            $tipoAporte = 'BENEFICIO SOCIAL';
                        } elseif ($this->id_tipo_aporte == 3) {
                            $tipoAporte = 'PLANILLA ADICIONAL';
                        } 
                            elseif ($this->id_tipo_aporte == 4) {
                            $tipoAporte = 'RETROACTIVO';
                        }
                        

                        if ($rol) {
                            $receptores = RolUsuario::where('role_id', $rol->id)->get();
                            foreach ($receptores as $receptor) {
                                $nuevaNotificacion = NotificacionUsuarios::create([
                                    'estado' => 'NO LEIDO',
                                    'prioridades' => 1,
                                    'id_usu_emisor' => auth()->user()->id,
                                    'id_usu_receptor' => $receptor->model_id,
                                    'fecha_hora_enviada' => Carbon::now(),
                                    'asunto' => 'APORTES - PLANILLA REGISTRADA',
                                    'mensaje' => 'La institución ' . $nomInstitucion . ' subió planilla del mes de: ' . $nombreMes . ' , ' . $this->gestion . ' .De tipo: ' . $tipoAporte,
                                ]);
                            }
                        }
                        // FIN DE NOTIFICACION 
                    } 

                    $correo_ins = Institucion::where('id', '=', $this->id_institucion)->first();

                    //ftp
                    $customFileName = $correo_ins->nombre . '-' . $this->id_mes . '-' . $this->id_tipo_aporte . '-' . $this->gestion . '.' . $file->getClientOriginalExtension();
                    $filePath = 'Aportes/Planillas/' . $customFileName;
                    $fileContents = file_get_contents($file->getRealPath());
                    Storage::disk('ftp')->put($filePath, $fileContents);

                    //mail
                    $asunto = 'Sistema Sighov2 - Unidad de Aportes';
                    $email = $correo_ins->correo;
                    $correo = new PlanillasInstitucionalesMailable($asunto, $correo_ins, $this->id_mes, $this->gestion, $this->id_tipo_aporte);
                    try {
                        Mail::to($email)->send($correo);
                    } catch (\Exception $e) {
                        // Log del error (opcional)
                        Log::error('No se pudo enviar el correo: ' . $e->getMessage());
                    }

                    $this->reset(['id_institucion', 'id_mes', 'id_tipo_aporte', 'gestion', 'planilla']);
                    $this->esperandoDatos = false;
                    $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionalesExterno');

                    // throw new \Exception('Error forzado despues de la importacion.');
                    DB::commit();
                    $this->dispatch('SubirPlanillaExitoso');
                    
                

            }
            
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($e->getMessage() === 'Ya existe una planilla con estas características.') {
                $this->dispatch('ErrorYaExistePlanilla');
            } else {
                $this->dispatch('ErrorGeneral');
            }
        }
    }

    public function funcion_mensaje_preliqui(){
        $this->dispatch('mensaje_preliquidacion');
    }

     public function buscar_comp_umsa($id_mes, $gestion, $reintegro, $beneficio, $planilla_adicional, $retroactivo){

        $comp_umsa = Comprobante::where('id_institucion', '=', 114)
            ->where('id_mes', '=', $id_mes)
            ->where('gestion', '=', $gestion)
            ->where('reintegro', '=', $reintegro)
            ->where('beneficio_social', '=', $beneficio)
            ->where('planilla_adicional', '=', $planilla_adicional)
            ->where('retroactivo', '=', $retroactivo)
            ->where('estado_umsa', '=', false)
            ->first();
        return $comp_umsa;
    }

    public function crear_comp_umsa($gestion, $eventuales, $permanentes, $total_ganado_sum, $total_aporte_sum, 
    $reintegro, $beneficio, $planilla_adicional, $retroactivo, $id_mes, $total_compensacion_sum, $nro_lote){

        $new_comp_umsa = Comprobante::create([
            'id_institucion' => 114,
            'gestion' => $gestion,
            'id_estado' => 1,
            'cantidad_eventuales' => $eventuales,
            'cantidad_permanentes' => $permanentes,
            'total_salario_cotizable' => $total_ganado_sum,
            'importe_aporte_patronal' => $total_aporte_sum,
            'reintegro' => $reintegro,
            'beneficio_social' => $beneficio,
            'planilla_adicional' => $planilla_adicional,
            'retroactivo' => $retroactivo,
            'id_mes' => $id_mes,
            'estado_umsa' => false,
            'total_compensacion' => $total_compensacion_sum,
            'id_user_created' => auth()->user()->id,
        ]); 

        $id_comprobante = $new_comp_umsa->id;

        // Actualizamos el aporte_i_temp con id_comprobante  
                AporteInstitucionalTemp::where('lote_comp', $nro_lote)
                ->update(['id_comp' => $id_comprobante]);

        // Actualizamos el lote_comp a null ya que tenemos el id_comp 
            AporteInstitucionalTemp::where('id_comp', $id_comprobante)
            ->update(['lote_comp' => null]);

    }
    
    public function act_comp_umsa($id, $a_eventuales, $a_permanentes, $a_total_salario_cotizable,
    $a_importe,$a_total_compensacion, $a_id_user_updated, $nro_lote ){

        $act_comp_umsa = Comprobante::find($id);
            $act_comp_umsa->cantidad_eventuales = $a_eventuales;
            $act_comp_umsa->cantidad_permanentes = $a_permanentes;
            $act_comp_umsa->total_salario_cotizable = $a_total_salario_cotizable;
            $act_comp_umsa->importe_aporte_patronal = $a_importe;
            $act_comp_umsa->total_compensacion = $a_total_compensacion;
            $act_comp_umsa->estado_umsa = true;
            $act_comp_umsa->id_user_updated = $a_id_user_updated;
            $act_comp_umsa->save();

            $id_comprobante = $act_comp_umsa->id; 
        // Actualizamos el aporte_i_temp con id_comprobante  
                AporteInstitucionalTemp::where('lote_comp', $nro_lote)
                ->update(['id_comp' => $id_comprobante]);

        // Actualizamos el lote_comp a null ya que tenemos el id_comp 
            AporteInstitucionalTemp::where('id_comp', $id_comprobante)
            ->update(['lote_comp' => null]);

    }

    public function crear_ap_insti_temp_umsa($id_mes, $gestion, $id_tipo_aporte, $porcentaje, $umsa_state){
        try {
            // Aqui creamos un nuevo lote_comprobante unico de 8 digitos
                $timestamp_short = substr(time(), -6); // Obtener los últimos 6 dígitos del timestamp
                $random_part = Str::random(2); // Generar 2 caracteres aleatorios
                $this->lote_comp_umsa = $timestamp_short . $random_part;

            $file = $this->planilla;
            Excel::import(new ProbandoImports(
                114,
                $id_mes,
                $id_tipo_aporte,
                $gestion,
                $porcentaje,
                $umsa_state, $this->lote_comp_umsa), $file);
        } catch (\Exception $e) { 
            $this->dispatch('errorImportacion', mensaje: $e->getMessage());
            return;
        }

    }
}
