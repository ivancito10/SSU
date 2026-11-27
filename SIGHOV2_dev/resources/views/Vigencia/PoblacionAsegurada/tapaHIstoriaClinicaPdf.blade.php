<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> TAPA HISTORIA CLÍNICA </title>
     
</head>
<style>
    body {
        font-family: Arial, sans-serif;
    }
</style>

<body>

    <div style="margin-top:6cm; text-align:center;">
        <div style="border: 1px solid black; border-radius: 5px; display:inline-block; padding: 5px; text-align:center;">
            <p style="font-size:85px; margin:0; text-align:center;"> 
                <b>
                    @if(!empty($datos_persona->matricula_seguro) || !empty($codigo_institucional))
                        {{ $datos_persona->matricula_seguro ?? '' }}
                        @if(!empty($codigo_institucional))
                            - {{ $codigo_institucional }}
                        @endif
                    @else
                        N/A
                    @endif
                </b> 
            </p>
        </div> 
    </div>

     
    <div>
    
        <table style="width: 100%;">
            <tbody>
                <tr style="vertical-align: top; margin-top:0px;">
                    <td width="30%" style="text-align:center; "> 
                        <img src="data:image/png;base64,{{ $qrCode }}" 
                        alt="Código QR" 
                        width="100px" 
                        height="95px" class="qr" 
                        style="display:block; margin:0; padding:0;"/> 
                    </td>
                    <td width="55%" styke="text-align:center;">
                        <div style="text-align:center;">
                            <p style="margin:0; padding:0;"><b> {{$datos_persona->tipoAseguradoPersona->tipo_asegurado}}  </b>   </p>
                        </div>
                        <div style="border: 1px solid black;  padding: 1px; text-align:center; font-size:19px;">
                            <p style="margin:1;">
                                @if($datos_persona->tipoAseguradoPersona->tipo_asegurado != 'ESTUDIANTE' && $acronimo_institucion != 'sin dato')
                                    <b>{{$acronimo_institucion}} </b>
                                @elseif($datos_persona->tipoAseguradoPersona->tipo_asegurado == 'ESTUDIANTE')
                                    <b> UMSA - ESTUDIANTE</b>
                                @elseif($datos_persona->tipoAseguradoPersona->tipo_asegurado == 'TITULAR CONVENIO')
                                    <b> EMERGENCIAS</b> 
                                @elseif($sigla_seguro_interior != '')
                                    <b> {{$sigla_seguro_interior}}</b>
                                @else
                                    <b>sin dato</b>
                                @endif
                            </p>
                        </div>
                        <p style="font-weight:bold; font-size:17px; text-align:center; font-size:17px;"> GRUPO SANGUÍNEO:  @if($datos_persona->grupo_sanguineo != null) {{$datos_persona->grupo_sanguineo}} @else SIN GRUPO @endif</p>
                        
                    </td>
                    <td width="15%">  </td>
                </tr>
            </tbody>
        </table>
        <br>
        <table style="width: 100%; font-size:17px;">
            <tbody>
                <tr>
                    <td width="100%"> <b> ALERGIAS: </b>
                        @if($datos_persona->alergias != null)
                            <span>
                                {{ $datos_persona->alergias }}
                            </span>
                        @else
                            _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _
                        @endif

                    </td>
                     
                </tr>
            </tbody>
        </table>
        <br> 
        <table style="width: 100%; font-size:19px; text-align:center;">
            <tbody>
                <tr>
                    <td width="100%"> <b> HOSPITAL <br> 
                        SEGURO SOCIAL UNIVERSITARIO <br>  
                        LA PAZ <br> <br>
                        EXPEDIENTE CLÍNICO 
                        </b> 
                    </td>
                     
                </tr>
            </tbody>
        </table>
        
        <table style="width: 100%; border: 2px solid black; border-collapse: collapse; text-align:center;">
            <tbody>
                <tr>
                    <td colspan="1" style="width: 30%;"><b>{{$datos_persona->p_apellido}} </b></td>
                    <td colspan="1" style="width: 30%;"><b>{{$datos_persona->s_apellido}}</b></td>
                    <td colspan="3" style="width: 40%;"><b>{{$datos_persona->nombres}}</b></td>
                </tr>

                <tr>
                    <td colspan="5" style="border-top: 1px solid black; padding: 2px;"></td>
                </tr>

                <tr>
                    <td colspan="1" style="width: 30%; font-size:13px;"><em>APELLIDO PATERNO</em></td>
                    <td colspan="1" style="width: 30%; font-size:13px;"><em>APELLIDO MATERNO</em></td>
                    <td colspan="3" style="width: 40%; font-size:13px;"><em>NOMBRES</em></td>
                </tr>

                <tr>
                    <td colspan="5" style="border-top: 1px solid black; border-bottom: 1px solid black; padding: 2px;"></td>
                </tr>

                <tr>
                    <td style="padding-right:15px; font-size: 16px; " colspan="3">
                         
                        <table style="width: 100%; ">
                            <tbody>
                                <tr>
                                    <td style="padding: 8px; "><b>ASEGURADO:</b></td>
                                    <td>
                                        @if( ($datos_persona->tipoAseguradoPersona->tipo_asegurado == 'TITULAR' && $clase_aportante != 'RENTISTA')  || $datos_persona->tipoAseguradoPersona->tipo_asegurado == 'ESTUDIANTE' || $clase_aportante == 'VOLUNTARIO' || $datos_persona->tipoAseguradoPersona->tipo_asegurado == 'TITULAR CONVENIO' || $datos_persona->tipoAseguradoPersona->tipo_asegurado == 'TITULAR DEL INTERIOR' )
                                        <div style="width:15px; height:15px; background-color:black; display:inline-block; border:1px solid #000;"></div>
                                        @else
                                        <div style="width:15px; height:15px; display:inline-block; border:1px solid #000;"></div>
                                        @endif
                                    </td>
                                    <td style="padding: 8px; "><b>DOCENTE:</b></td>
                                    <td>
                                        @if($valor == 1)
                                        <div style="width:15px; height:15px; background-color:black; display:inline-block; border:1px solid #000;"></div>
                                        @else
                                        <div style="width:15px; height:15px; display:inline-block; border:1px solid #000;"></div> 
                                        @endif
                                    </td>
                                    <td style="padding: 8px; "><b>SOLTERO:</b></td>
                                    <td>
                                        @if($datos_persona->id_estado_civil == 1)
                                        <div style="width:15px; height:15px; background-color:black; display:inline-block; border:1px solid #000;"></div>
                                        @else
                                        <div style="width:15px; height:15px; display:inline-block; border:1px solid #000;"></div>
                                        @endif

                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px; "><b>BENEFICIARIO:</b></td>
                                    <td>
                                        @if(str_starts_with($datos_persona->tipoAseguradoPersona->tipo_asegurado, 'BENEFICIARIO'))
                                            <div style="width:15px; height:15px; background-color:black; display:inline-block; border:1px solid #000;"></div>
                                        @else
                                            <div style="width:15px; height:15px; display:inline-block; border:1px solid #000;"></div>
                                        @endif

                                    </td>
                                    <td style="padding: 8px; "><b>ADMINISTRATIVO:</b></td>
                                    <td>
                                        @if($valor == 2 && $clase_aportante != 'VOLUNTARIO')
                                        <div style="width:15px; height:15px; background-color:black; display:inline-block; border:1px solid #000;"></div>
                                        @else
                                        <div style="width:15px; height:15px; display:inline-block; border:1px solid #000;"></div> 
                                        @endif 
                                    </td>
                                    <td style="padding: 8px; "><b>CASADO:</b></td>
                                    <td>
                                        @if($datos_persona->id_estado_civil == 2)
                                        <div style="width:15px; height:15px; background-color:black; display:inline-block; border:1px solid #000;"></div>
                                        @else
                                        <div style="width:15px; height:15px; display:inline-block; border:1px solid #000;"></div>
                                        @endif 
                                    </td>
                                </tr> 
                                <tr> 
                                    <td style="padding: 8px; "><b>RENTISTA:</b></td>
                                    <td>
                                        @if($clase_aportante == 'RENTISTA')
                                        <div style="width:15px; height:15px; background-color:black; display:inline-block; border:1px solid #000;"></div>
                                        @else
                                        <div style="width:15px; height:15px; display:inline-block; border:1px solid #000;"></div>
                                        @endif
                                    </td>
                                    <td style="padding: 8px; "><b>VOLUNTARIO:</b></td>
                                    <td>
                                        @if($datos_persona->tipoAseguradoPersona->tipo_asegurado != 'ESTUDIANTE')
                                            @if($clase_aportante == 'VOLUNTARIO')
                                            <div style="width:15px; height:15px; background-color:black; display:inline-block; border:1px solid #000;"></div>
                                            @else
                                            <div style="width:15px; height:15px; display:inline-block; border:1px solid #000;"></div>
                                            @endif
                                        @else
                                            <div style="width:15px; height:15px; display:inline-block; border:1px solid #000;"></div>
                                        @endif 
                                    </td>
                                    <td style="padding: 8px; "><b>VIUDO:</b></td>
                                    <td>
                                        @if($datos_persona->id_estado_civil == 3)
                                        <div style="width:15px; height:15px; background-color:black; display:inline-block; border:1px solid #000;"></div>
                                        @else
                                        <div style="width:15px; height:15px; display:inline-block; border:1px solid #000;"></div>
                                        @endif
                                    </td> 
                                </tr>
                                <tr> 
                                    <td style="padding: 8px; "> </td>
                                    <td>  </td>
                                    <td style="padding: 8px; "><b> </b></td>
                                    <td>  </td>
                                    <td style="padding: 8px; "><b>DIVORCIADO:</b></td>
                                    <td>
                                        @if($datos_persona->id_estado_civil == 4)
                                        <div style="width:15px; height:15px; background-color:black; display:inline-block; border:1px solid #000;"></div>
                                        @else
                                        <div style="width:15px; height:15px; display:inline-block; border:1px solid #000;"></div>
                                        @endif 
                                    </td> 
                                </tr>

                            </tbody>
                        </table>
                    </td> 
                     

                    <td style=" padding-right:15px;" colspan="2">
                        <br>
                        <table style="width: 100%; border: 2px solid black; border-collapse: collapse; text-align:center; ">
                            <tbody>
                                <tr>
                                    <td style="padding: 8px; font-size:17px;"><b>{{$datos_persona->matricula_seguro}} @if($codigo_institucional != null) - {{$codigo_institucional}} @endif</b></td>
                                </tr>
                                <tr>
                                    <td style="border-top: 2px solid black; padding: 8px; font-size:12px; margin:0; font-size:13px;">MATRÍCULA SSU</td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                        <table style="width: 100%; border: 2px solid black; border-collapse: collapse; text-align:center; ">
                            <tbody>
                                <tr>
                                    <td style="padding: 8px; font-size:17px;"><b>{{$datos_persona->ci}} @if($datos_persona->complemento != null) - {{$datos_persona->complemento}} @endif  {{$datos_persona->departamentoPersona->sigla ?? ''}}</b></td>
                                </tr>
                                <tr>
                                    <td style="border-top: 2px solid black; padding: 8px; font-size:12px; margin:0; font-size:13px;">CARNET DE IDENTIDAD</td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                    </td>
                </tr>

                <tr>
                    <td colspan="5" style="border-top: 1px solid black; border-bottom: 1px solid black; padding: 2px;"></td>
                </tr>

                <tr>
                    <td colspan="5" style="width: 100%; text-align:left;"> DOMICILIO:  </td>
                </tr>

                @if($nro_colspan == 2)
                    <tr style="font-size:14px;">
                        <td colspan="2"><b>{{$direccion}} </b></td> 
                        <td colspan="2"><b>{{$zona}}</b></td>
                        <td colspan="1"><b>{{$telefono}}</b></td>
                    </tr>

                    <tr>
                        <td colspan="5" style="border-top: 1px solid black; padding: 2px;"></td>
                    </tr>
                    
                    <tr style="font-size:15px;">
                        <td colspan="1"><b>Avenida/Calle </b></td>
                        <td colspan="1"><b>Número</b></td>
                        <td colspan="2"><b>Zona</b></td>
                        <td colspan="1"><b>Teléfonos</b></td>
                    </tr>

                @else
 
                    <tr style="font-size:14px;">
                        <td colspan="1"><b>{{$direccion}} </b></td>
                        <td colspan="1"><b>{{$nro_vivienda}}</b></td>
                        <td colspan="2"><b>{{$zona}}</b></td>
                        <td colspan="1"><b>{{$telefono}}</b></td>
                    </tr>

                    <tr>
                        <td colspan="5" style="border-top: 1px solid black; padding: 2px;"></td>
                    </tr>
                    
                    <tr style="font-size:15px;">
                        <td colspan="1"><b>Avenida/Calle </b></td>
                        <td colspan="1"><b>Número</b></td>
                        <td colspan="2"><b>Zona</b></td>
                        <td colspan="1"><b>Teléfonos</b></td>
                    </tr>
                @endif

                <tr>
                    <td colspan="5" style="border-top: 1px solid black; padding: 2px;"></td>
                </tr>

                <tr>
                    <td colspan="3" style="text-align:left; padding-top:25px; font-size:15px;">
                        @if($persona_ben_tit !=null ) 
                            <b>{{$persona_ben_tit->p_apellido}} {{$persona_ben_tit->s_apellido}} {{$persona_ben_tit->nombres}}  </b>
                        @endif
                    </td>
                    <td colspan="2" style="text-align:center; padding-top:25px; font-size:18px;">
                        @if($persona_ben_tit !=null )
                        <b> {{$persona_ben_tit->matricula_seguro}}
                            @if($codigo_institucional_titular !=null ) 
                                -
                                {{$codigo_institucional_titular}} 
                            @endif
                        </b>    
                        @endif

                    </td>
                </tr>

                <tr>
                    <td colspan="5" style="border-top: 1px solid black; padding: 2px;"></td>
                </tr>

                <tr style="font-size:13px;">
                    <td colspan="3" style="text-align:left;"><b>BENEFICIARIO DE: </b></td>
                    <td colspan="2" style="text-align:center;"><b> MATRÍCULA TITULAR SSU</b></td>
                </tr>
            </tbody>
        </table>

          
        
    </div>
    
 
     
</body>

</html>
