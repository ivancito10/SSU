<?php

namespace App\Imports\Aportes;

use App\Models\Aportes\AporteInstitucional;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Administracion\Persona;
use Maatwebsite\Excel\Concerns\WithStartRow;

class AportesPlanillaImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
         
       
   
    }

}
