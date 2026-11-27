<div>
    <div style="position: relative; ">
        <div style="position: absolute; top:-100px;bottom:0px; right: 200px;">
            <x-volver-button wire:click="volverAInicio">INICIO</x-volver-button>
            <x-salir-dos-button wire:click="de2BusquedaA1Ingreso">ATRÁS</x-salir-dos-button>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-10">
            <div class="card-vista-comun-temp">
                <p style="color:#eded3a; font-size:30px; align-items:left"><b>REALIZA TU BUSQUEDA</b></p>
            </div>
            <div style="height: 0.5rem;"></div>
            <div class="row">
                <div class="col-lg-7">
                    <div class="card-vista-comun-temp">
                        <div class="row">
                            <div class="col-lg-12">
                                <p
                                    style="font-style: italic; font-size: 22px; color: #eded3a; margin-top: 0;text-align: left;">
                                    <b>Ingrese su carnet y complemento (solo si tiene).</b>
                                </p>
                            </div>
                            <div class="col-lg-6 m-0 " style="text-align:center">
                                <label for="carnetBusqueda" style="color:#f4f6f7;font-size:25px;"> <b>CARNET DE
                                        IDENTIDAD</b>
                                </label>
                                <input class="form-control" id="carnetBusqueda" inputmode="numeric" type="number"
                                    style="border-radius:20px;color:black;padding: 20px;font-size:25px;"
                                    wire:model=carnetBusqueda wire:keydown.enter="busquedaAsegurado"
                                    placeholder="Ejemplo: 12345678 " autocomplete="off" autocorrect="off"
                                    spellcheck="false" name="no-autocomplete"
                                    oninput="if(this.value.length > 12) this.value = this.value.slice(0, 12)">
                            </div>
                            <div class="col-lg-4  m-0" style="text-align:center">
                                <label for="complementoBusqueda" style="color:#f4f6f7;font-size:25px;"><b>
                                        COMPLEMENTO</b>
                                </label>
                                <input class="form-control" id="complementoBusqueda"
                                    style="border-radius:20px;color:black;padding: 20px;font-size:25px;"
                                    wire:model="complementoBusqueda" wire:keydown.enter="busquedaAsegurado"
                                    placeholder="Ejemplo: 1U" oninput="this.value = this.value.toUpperCase()"
                                    autocomplete="off" autocorrect="off" spellcheck="false" name="no-autocomplete"
                                    maxlength="2">
                            </div>
                            <div class="col-lg-2">
                                <br><br>
                                <x-buscar-button wire:click="busquedaAsegurado">BUSCAR</x-buscar-button>
                            </div>
                        </div>
                        <div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card-vista-comun-temp">
                        <div class="row">
                            <div class="col-lg-12">
                                <p
                                    style="font-style: italic; font-size: 22px; color: #eded3a; margin-top: 0;text-align: left;">
                                    <b>Ingrese su matrícula de asegurado.</b>
                                </p>
                            </div>

                            <div class="col-lg-9 m-0 " style="text-align:center">
                                <label for="matriculaBusqueda" style="color:#f4f6f7;font-size:25px; "> <b>MATRÍCULA
                                        SEGURO</b>
                                </label>
                                <input class="form-control" id="matriculaBusqueda"
                                    style="border-radius:20px;color:black;padding: 20px;font-size:25px; "
                                    wire:model=matriculaBusqueda wire:keydown.enter="busquedaAsegurado"
                                    placeholder="Ejemplo:112233VML" autocomplete="off" autocorrect="off"
                                    oninput="this.value = this.value.toUpperCase()" spellcheck="false"
                                    name="no-autocomplete" maxlength="9">
                            </div>
                            <div class="col-lg-3 ">
                                <br><br>
                                <x-buscar-button wire:click="busquedaAsegurado">BUSCAR</x-buscar-button>
                            </div>
                            <div wire:loading wire:target="busquedaAsegurado">
                                <x-cargando-dos />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--resultados de busqueda-->
            @if (!empty($resultadoBusqueda))
                <div style="height: 0.5rem;"></div>
                <div class="card-vista-comun-temp">
                    <br>
                    <p style="font-style: italic; font-size: 22px; color: #eded3a; text-align: right;">
                        <b> Elija sus datos presionando el botón seleccionar.</b>
                    </p>

                    <table class="tabla-resultados">
                        <thead>
                            <tr>
                                <th style="width: 30%;">NOMBRE</th>
                                <th style="width: 30%;">APELLIDO
                                    PATERNO</th>
                                <th style="width: 25%;">APELLIDO
                                    MATERNO </th>
                                <th style="width: 15%;">ACCIÓN</th>
                            </tr>
                        </thead>
                    </table>
                    <div style="height: 0.5rem;"></div>
                    <div class="scroll-container-busqueda">
                        <table class="tabla-resultados">
                            <tbody class="list">
                                @foreach ($resultadoBusqueda as $key => $resultado)
                                    {{-- @if ($resultado['tipo_asegurado'] != 'NO ASEGURADO') --}}
                                    <tr
                                        style="color:#000000; background-color: rgba(255, 255, 255, 0.2); text-align:center">
                                        <td class="fila-seleccionar">
                                            {{ $resultado['nombres'] }} </td>
                                        <td class="fila-seleccionar"> {{ $resultado['p_apellido'] }}</td>
                                        <td class="fila-seleccionar"> {{ $resultado['s_apellido'] }}</td>
                                        <td class="fila-seleccionar">
                                            <x-seleccionar-button
                                                wire:click="seleccionarPersona({{ $resultado['id_persona'] }})">
                                                <b> SELECCIONAR</b>
                                            </x-seleccionar-button>

                                        </td>
                                    </tr>
                                    {{-- -@endif --}}
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>


    <style>
        .scroll-container-busqueda {
            max-height: 300px;
            overflow-y: auto;
            padding-right: 2px;
        }

        .fila-seleccionar {
            padding: 15px;
            width: 30%;
            color: rgb(255, 255, 255);
            font-weight: 800;
            font-size: 30px;
            border-bottom: 1px solid #ccc;
        }

        .tabla-resultados {
            width: 100%;
            border-collapse: separate;
            /* ¡Importante! Necesario para que border-radius funcione */
            border-spacing: 0;
            border: 1px solid #ccc;
            /* Opcional: si quieres un borde visible */
            border-radius: 10px;
            overflow: hidden;
            /* Esto ayuda si hay fondo o contenido saliéndose */
        }

        /* Sticky para los th */
        .tabla-resultados thead th {
            position: sticky;
            top: 0;
            background-color: rgba(255, 255, 255, 0.9);
            /* color de fondo para que no sea transparente */
            z-index: 1;
            /* por si hay celdas que se superpongan */
            padding: 10px;
            text-align: center;
        }
    </style>

</div>
