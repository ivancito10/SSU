<?php

namespace App\Imports\Aportes;

use App\Models\Aportes\Ufv;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class UfvImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        //Pregunta si existe o no el registro
        $comprobarUfv = Ufv::where('fecha', '=', $row[1])
            ->where('estado', '=', true)
            ->first();

        if (!$comprobarUfv) {
            //Inserta en la tabla Ufv
            try {
                Ufv::create([
                    'valor' => $row[0],
                    'fecha' => $row[1],
                    'estado' => true,
                    'id_user_created' => auth()->user()->id,
                    'id_user_updated' => auth()->user()->id,
                ]);
            } catch (\Throwable $th) {
                throw new \Exception('Ocurrió un error al procesar los datos.');
            }
        }   
    }

    public function startRow(): int
    {
        return 2;
    }
}
