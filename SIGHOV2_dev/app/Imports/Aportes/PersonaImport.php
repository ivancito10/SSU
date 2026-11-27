<?php

namespace App\Imports\Aportes;

use App\Services\ValidarPersona;
use App\Models\Administracion\Persona;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class PersonaImport implements ToModel, WithStartRow, WithBatchInserts

{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    private $id_persona;

    private $ci;
    private $complemento;
    private $p_apellido;
    private $s_apellido;
    private $nombres;
    private $fecha_nacimiento;
    private $estado_asuss;
    public $leidos = [];
    // public $observados = [];
    public $extrangeros = [];
    public $conComplementos = [];
    public function model(array $row)
    {

        // try {
        $row[3] = trim($row[3]);
        //* MANDAMOS A ANALIZAR EL CI ANTES DE RALIZAR CUALQUIER ACCION 
        $documento = $this->analizarCI($row[3]);
        //* COLOCAMOS EL CI YA SIN COMPLEMENTO O EXTENSION DE EXTRANJERO 
        $this->ci = $documento['ci'];
        $this->complemento = $documento['complemento'];
        $verificar_persona = null;
        // Insertar en la tabla Persona
        $verificar_persona = Persona::where('ci', '=', $this->ci)
            ->where('complemento', $this->complemento)
            ->orderBy('id', 'desc')
            ->first();
        if ($verificar_persona == null) {

            $respuesta = new ValidarPersona();
            $dato = $respuesta->buscarPersona($this->ci, $this->complemento);
            if (isset($dato['datosPersonaEnFormatoJson'])) {
                $this->ci = $dato['datosPersonaEnFormatoJson']['numeroDocumento'];
                $this->complemento = $dato['datosPersonaEnFormatoJson']['Complemento'];
                $this->p_apellido = $dato['datosPersonaEnFormatoJson']['primerApellido'];
                $this->s_apellido = $dato['datosPersonaEnFormatoJson']['segundoApellido'];
                $this->nombres = $dato['datosPersonaEnFormatoJson']['nombres'];
                $this->fecha_nacimiento = $dato['datosPersonaEnFormatoJson']['fechaNacimiento'];
                $this->estado_asuss = 1;
            } else {
                $this->ci = $documento['ci'];
                $this->complemento = trim("");
                $this->p_apellido = trim($row[0]);
                $this->s_apellido = trim($row[1]);
                $this->nombres = trim($row[2]);
                $this->fecha_nacimiento = null;
                $this->estado_asuss = 0;
            }

            if($this->complemento == null){
                $clave_unica = $this->ci;
            } else {
                $clave_unica = $this->ci.$this->complemento;
            }

            $persona = Persona::create([
                'p_apellido' =>  $this->p_apellido,
                's_apellido' =>  $this->s_apellido,
                'nombres' =>  $this->nombres,
                'ci' => $this->ci,
                'complemento' => $this->complemento,
                'fecha_nacimiento' => $this->fecha_nacimiento,
                'estado_asuss' => $this->estado_asuss,
                'clave_unica' => $clave_unica,
                // 'id_tipo_asegurado' => 22,
                // 'id_user_created' => auth()->user()->id,
            ]);
        }
        //TODO: funcion para actualizar
        //* ------------------ funcion para actualizar a la persona registrada si no esta verificada por la asuss
        if ($verificar_persona) {
            if (!$verificar_persona->estado_asuss) {
                $respuesta = new ValidarPersona();
                $dato = $respuesta->buscarPersona($verificar_persona->ci, $verificar_persona->complemento);
                if (isset($dato['datosPersonaEnFormatoJson'])) {
                    $this->ci = $dato['datosPersonaEnFormatoJson']['numeroDocumento'];
                    $this->complemento = $dato['datosPersonaEnFormatoJson']['Complemento'];
                    $this->p_apellido = $dato['datosPersonaEnFormatoJson']['primerApellido'];
                    $this->s_apellido = $dato['datosPersonaEnFormatoJson']['segundoApellido'];
                    $this->nombres = $dato['datosPersonaEnFormatoJson']['nombres'];
                    $this->fecha_nacimiento = $dato['datosPersonaEnFormatoJson']['fechaNacimiento'];
                    $this->estado_asuss = 1;
                    $persona = Persona::where('id', $verificar_persona->id)
                        ->update([
                            'p_apellido' =>  $this->p_apellido,
                            's_apellido' =>  $this->s_apellido,
                            'nombres' =>  $this->nombres,
                            'ci' => (int) $this->ci,
                            'complemento' => $this->complemento,
                            'fecha_nacimiento' => $this->fecha_nacimiento,
                            'estado_asuss' => $this->estado_asuss,
                            // 'id_tipo_asegurado' => 22,
                            // 'id_user_created' => auth()->user()->id,
                        ]);
                }
            }
        }
        // -------------------------------------
        // }
        // }
        // } catch (\Throwable $th) {
        //     throw new \Exception('Ocurrió un error al procesar los datos.');
        // }
        // return response()->json($this->leidos, 200);
    }
    public function analizarCI($cadena)
    {
        $ci = $cadena;
        // //? ELIMINAMOS EL LOS PRIMEROS CARACTERES DEL CI EXTRANJERO 
        if (strpos($cadena, "E-") !== false) {
            array_push($this->extrangeros, $cadena);
            // El CI comienza con "E-", elimina los dos primeros caracteres
            $cadena = substr($cadena, 2);
            $ci = $cadena;
        }
        //? SEPARAMOS EL CI DEL COMPLEMENTO 
        $posicionGuion = strpos($cadena, "-");
        $complemento = "";
        if ($posicionGuion !== false) {
            $complemento = substr($cadena, $posicionGuion + 1, 2);
            array_push($this->conComplementos, $cadena);
            $ci = substr($cadena, 0, -3);
        }
        // $ci = preg_replace('/[^0-9]/', '', $cadena);
        $documento = [
            'ci' => (int) $ci,
            'complemento' => $complemento,
        ];
        return $documento;
    }
    public function devolverArray()
    {
        $datos = [
            'leidos' => $this->leidos,
            'extrangeros' => $this->extrangeros,
            'conComplementos' => $this->conComplementos,
        ];
        return $datos;
    }
    public function startRow(): int
    {
        // Indica que la importación debe comenzar desde la segunda fila (índice 2)
        return 2;
    }
    public function batchSize(): int
    {
        return 500;
    }
    public function chunkSize(): int
    {
        return 500;
    }
}
