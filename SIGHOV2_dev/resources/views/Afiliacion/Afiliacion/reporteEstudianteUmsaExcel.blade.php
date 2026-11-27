 <style>
     .cabecera {
         background-color: #922b21;
         color: white;
         text-align: center;
         font-size: 12px;
     }

     .celda {
         font-size: 11px;
         padding: 4px;
     }
 </style>

 <h3 style="text-align: center;">REPORTE DE ESTUDIANTES - {{ $mesNombre }} {{ $año }}</h3>

 <table border="1" style="width: 100%; border-collapse: collapse;">
     <thead>
         <tr>
             <th class="cabecera">NRO</th>
             <th class="cabecera">MATRÍCULA</th>
             <th class="cabecera">CI</th>
             <th class="cabecera">NOMBRE COMPLETO</th>
             <th class="cabecera">R.U.</th>
             <th class="cabecera">FACULTAD</th>
             <th class="cabecera">CARRERA</th>
         </tr>
     </thead>
     <tbody>
         @php $count = 0; @endphp
         @foreach ($estudiantes_registrados as $estudiante)
             <tr>
                 <td class="celda">{{ ++$count }}</td>
                 <td class="celda">{{ $estudiante->personaEstudiante->matricula_seguro }}</td>
                 <td class="celda">
                     {{ $estudiante->personaEstudiante->ci . $estudiante->personaEstudiante->complemento }}</td>
                 <td class="celda">
                     {{ $estudiante->personaEstudiante->p_apellido }}
                     {{ $estudiante->personaEstudiante->s_apellido }}
                     {{ $estudiante->personaEstudiante->nombres }}
                 </td>
                 <td class="celda">{{ $estudiante->registro_universitario }}</td>
                 <td class="celda">{{ $estudiante->carreraEstudiante->facultadCarrera->nombre ?? 'SIN ACTUALIZAR' }}
                 </td>
                 <td class="celda">{{ $estudiante->carreraEstudiante->nombre ?? 'SIN ACTUALIZAR' }}</td>
             </tr>
         @endforeach
     </tbody>
 </table>
