<div>
    {{-- ENCABEZADO PRINCIPAL --}}
    <div style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e; padding:5px; border-radius:5px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);">
        <div style="display: flex; align-items: center; padding-left: 10px;">
            <h7 style="font-family: Verdana, sans-serif; color: white; margin: 0; text-transform: uppercase;">
                <i class="fas fa-syringe mr-2"></i> ENFERMERÍA - REGISTRAR INYECCIÓN
            </h7>
        </div>
        <x-atras-button wire:click="cancelar" style="margin-left: auto; padding-right:10px;">ATRÁS</x-atras-button>
    </div>
    <br>

    {{-- SELECTOR TIPO DE RECETA --}}
    <div class="row mb-3">
        <div class="col-lg-12">
            <div class="p-3 d-flex align-items-center shadow-sm" style="background-color: #f8fafc; border-radius: 5px; border: 1px solid #e2e8f0;">
                <strong style="color: #2471a3; font-size: 1.1rem;" class="mr-4">TIPO DE RECETA:</strong>
                <div class="btn-group shadow-sm" role="group">
                    <button type="button" class="btn {{ $tipoReceta === 'SISTEMA' ? 'btn-primary font-weight-bold' : 'btn-outline-primary' }}" wire:click="setTipoReceta('SISTEMA')">
                        <i class="fas fa-desktop mr-1"></i> DEL SISTEMA
                    </button>
                    <button type="button" class="btn {{ $tipoReceta === 'MANUAL' ? 'btn-primary font-weight-bold' : 'btn-outline-primary' }}" wire:click="setTipoReceta('MANUAL')">
                        <i class="fas fa-file-signature mr-1"></i> MANUAL FÍSICA
                    </button>
                </div>
                <div wire:loading wire:target="setTipoReceta" class="ml-3 text-primary font-weight-bold small">
                    <i class="fas fa-spinner fa-spin mr-1"></i> Cargando...
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- BLOQUE 1: MODO SISTEMA                                     --}}
    {{-- ========================================================== --}}
    @if ($tipoReceta === 'SISTEMA')
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <fieldset style="border: 1px solid #2471a3; border-radius: 5px; padding: 15px; position: relative;">
                    <legend style="color: #2471a3; font-size: 1.25rem; padding: 0; margin-left: 10px; display: inline-block; width: auto; font-weight: bold;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                        </svg>
                        <em>Buscar Receta Institucional:</em>
                        <span style="color: #cb4335;"><b>*</b></span>
                    </legend>
                    
                    <div class="row align-items-end">
                        <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                            <label for="nroRecetaBusqueda" class="font-weight-bold">NRO. RECETA: <span style="color:#cb4335;"><b>*</b></span></label>
                            <div class="input-group">
                                <input class="form-control input-comun @error('nroRecetaBusqueda') is-invalid @enderror"
                                       id="nroRecetaBusqueda" 
                                       wire:model.live.debounce.300ms="nroRecetaBusqueda" 
                                       wire:keydown.enter="buscarRecetaSistema"
                                       type="text"
                                       inputmode="numeric"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/^0+/, '').slice(0,10);"
                                       placeholder="Ingrese número de receta...">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary" wire:click="buscarRecetaSistema" wire:loading.attr="disabled">
                                        <span wire:loading.remove wire:target="buscarRecetaSistema"><i class="fas fa-search mr-1"></i> BUSCAR</span>
                                        <span wire:loading wire:target="buscarRecetaSistema"><i class="fas fa-spinner fa-spin"></i></span>
                                    </button>
                                </div>
                            </div>
                            @error('nroRecetaBusqueda')
                                <div class="text-danger mt-1"><b>{{ $message }}</b></div>
                            @enderror
                        </div>

                        @if (!empty($nombreCompletoPaciente))
                            <div class="col-lg-5 col-md-8 col-sm-12 col-12">
                                <label class="font-weight-bold">NOMBRE ASEGURADO:</label>
                                <input type="text" class="form-control input-comun font-weight-bold text-dark" style="background-color: #f8fafc;" value="{{ $nombreCompletoPaciente }}" disabled>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-12 col-12">
                                <label class="font-weight-bold">MATRÍCULA:</label>
                                <input type="text" class="form-control input-comun" style="background-color: #f8fafc;" value="{{ $matriculaPaciente }}" disabled>
                            </div>
                        @endif
                    </div>

                    @if (!empty($medicamentosRecetaSistema))
                        <hr>
                        <h6 class="font-weight-bold" style="color: #2471a3;">SELECCIONE MEDICAMENTO INYECCIÓN:</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered text-center mb-0">
                                <thead style="background-color: #2471a3; color: white;">
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>RECETA</th>
                                        <th>MEDICAMENTO INYECCIÓN</th>
                                        <th style="width: 220px;">VÍA PARENTERAL</th>
                                        <th style="width: 120px;">SELECCIONAR</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($medicamentosRecetaSistema as $idx => $med)
                                        <tr>
                                            <td style="vertical-align: middle;">{{ $idx + 1 }}</td>
                                            <td style="vertical-align: middle;"><span class="badge badge-light border">{{ $nroRecetaBusqueda }}</span></td>
                                            <td style="vertical-align: middle;" class="text-left font-weight-bold">{{ $med['nombre'] }}</td>
                                            <td style="vertical-align: middle;">
                                                <select class="form-control form-control-sm" wire:model="medicamentosRecetaSistema.{{ $idx }}.id_via_parenteral">
                                                    @foreach ($viasParenterales as $via)
                                                        <option value="{{ is_array($via) ? $via['id'] : $via->id }}">{{ is_array($via) ? $via['nombre'] : $via->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td style="vertical-align: middle;">
                                                <button type="button" class="btn btn-sm btn-success" wire:click="agregarInyeccionSistema({{ $idx }})">
                                                    <i class="fas fa-check"></i> Seleccionar
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </fieldset>
            </div>
        </div>
    @endif

    {{-- ========================================================== --}}
    {{-- BLOQUE 2: MODO MANUAL                                      --}}
    {{-- ========================================================== --}}
    @if ($tipoReceta === 'MANUAL')
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <fieldset style="border: 1px solid #2471a3; border-radius: 5px; padding: 15px; position: relative;">
                    <legend style="color: #2471a3; font-size: 1.25rem; padding: 0; margin-left: 10px; display: inline-block; width: auto; font-weight: bold;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                        </svg>
                        <em>Ingrese datos de la receta manual:</em>
                        <span style="color: #cb4335;"><b>*</b></span>
                    </legend>

                    <div class="row mb-3 align-items-end">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-12">
                            <label class="font-weight-bold">SELECCIONAR PACIENTE: <span style="color:#cb4335;"><b>*</b></span></label>
                            <button type="button" class="btn btn-block font-weight-bold @error('idPersona') btn-danger text-white @else btn-outline-primary @enderror" wire:click="abrirModalPacientes">
                                <i class="fas fa-search mr-1"></i> BUSCAR PACIENTE
                            </button>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <label class="font-weight-bold">NOMBRE COMPLETO DEL PACIENTE:</label>
                            <input type="text" class="form-control input-comun font-weight-bold text-dark @error('idPersona') is-invalid @enderror" style="background-color: #f8fafc;" value="{{ $nombreCompletoPaciente }}" placeholder="Seleccione un paciente..." disabled>
                            @error('idPersona') <div class="text-danger small mt-1"><b>{{ $message }}</b></div> @enderror
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-12">
                            <label class="font-weight-bold">MATRÍCULA:</label>
                            <input type="text" class="form-control input-comun" style="background-color: #f8fafc;" value="{{ $matriculaPaciente }}" placeholder="S/M" disabled>
                        </div>
                    </div>

                    <div class="row align-items-end">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-12 position-relative">
                            <label class="font-weight-bold text-dark"><i class="fas fa-user-md mr-1"></i> MÉDICO SOLICITANTE: <span style="color:#cb4335;"><b>*</b></span></label>
                            <div class="input-group">
                                <input type="text" class="form-control input-comun @error('idEspecialista') is-invalid @enderror" placeholder="Escriba el especialista..." wire:model.live.debounce.300ms="buscarEspecialista" @if($idEspecialista) readonly style="background-color: #f8fafc; font-weight: bold;" @endif>
                                @if($idEspecialista)
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-danger" wire:click="limpiarEspecialista"><i class="fas fa-times"></i></button>
                                    </div>
                                @endif
                            </div>
                            @error('idEspecialista') <div class="text-danger small mt-1"><b>{{ $message }}</b></div> @enderror
                            
                            @if($mostrarDropdownEspecialistas && count($especialistasEncontrados) > 0)
                                <ul class="list-group position-absolute w-100 shadow-lg" style="z-index: 1050; max-height: 230px; overflow-y: auto; top: 100%; left: 0;">
                                    @foreach($especialistasEncontrados as $esp)
                                        @php
                                            $idEsp = $esp['idEspecialista'] ?? $esp['id'] ?? null;
                                            $nom = $esp['nombreCompleto'] ?? $esp['nombre'] ?? '';
                                            $espNombre = is_array($esp['especialidad'] ?? null) ? ($esp['especialidad']['nombre'] ?? 'MEDICINA') : ($esp['especialidad'] ?? 'MEDICINA');
                                        @endphp
                                        <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2" style="cursor: pointer;" wire:click="seleccionarEspecialista({{ $idEsp }}, '{{ addslashes($nom) }}', '{{ addslashes($espNombre) }}')">
                                            <div><i class="fas fa-stethoscope text-secondary mr-2"></i><span class="font-weight-bold text-dark">{{ $nom }}</span></div>
                                            <span class="badge badge-primary px-2 py-1">{{ $espNombre }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @elseif($mostrarDropdownEspecialistas && strlen(trim($buscarEspecialista)) >= 2)
                                <div class="position-absolute w-100 bg-white border rounded p-2 text-muted shadow-sm" style="z-index: 1050; top: 100%; left: 0;"><small><i class="fas fa-info-circle mr-1"></i> No se encontraron especialistas.</small></div>
                            @endif
                        </div>
                        
                        <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                            <label class="font-weight-bold text-dark"><i class="fas fa-hashtag mr-1"></i> NRO. RECETA: <span style="color:#cb4335;"><b>*</b></span></label>
                            <input type="text" class="form-control input-comun @error('numeroRecetaManual') is-invalid @enderror" placeholder="Ej. 10452" wire:model="numeroRecetaManual">
                            @error('numeroRecetaManual') <div class="text-danger small mt-1"><b>{{ $message }}</b></div> @enderror
                        </div>
                        
                        <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                            <label class="font-weight-bold text-dark"><i class="fas fa-calendar-alt mr-1"></i> FECHA DE EMISIÓN: <span style="color:#cb4335;"><b>*</b></span></label>
                            <input type="date" class="form-control input-comun @error('fechaEmisionManual') is-invalid @enderror" wire:model="fechaEmisionManual">
                            @error('fechaEmisionManual') <div class="text-danger small mt-1"><b>{{ $message }}</b></div> @enderror
                        </div>
                    </div>

                    <hr>
                    <h6 class="font-weight-bold mb-3" style="color: #2471a3;">AGREGAR MEDICAMENTOS INYECCIÓN:</h6>
                    
                    <div class="row align-items-end">
                        <div class="col-lg-5 col-md-12 col-sm-12 col-12 position-relative">
                            <label class="font-weight-bold text-dark"><i class="fas fa-pills mr-1"></i> INYECCIÓN MEDICAMENTO: <span style="color:#cb4335;"><b>*</b></span></label>
                            <div class="input-group">
                                <input type="text" class="form-control input-comun @error('idMedicamentoManual') is-invalid @enderror" placeholder="Escriba código o nombre..." wire:model.live.debounce.300ms="buscarMedicamento" @if($idMedicamentoManual) readonly style="background-color: #f8fafc; font-weight: bold;" @endif>
                                @if($idMedicamentoManual)
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-danger" wire:click="limpiarMedicamentoInyectable"><i class="fas fa-times"></i></button>
                                    </div>
                                @endif
                            </div>
                            @error('idMedicamentoManual') <div class="text-danger small mt-1"><b>{{ $message }}</b></div> @enderror

                            @if($mostrarDropdownMedicamentos && count($medicamentosFiltrados) > 0)
                                <ul class="list-group position-absolute w-100 shadow-lg" style="z-index: 1050; max-height: 230px; overflow-y: auto; top: 100%; left: 0;">
                                    @foreach($medicamentosFiltrados as $med)
                                        @php
                                            $idMed = $med['id'];
                                            $cod = $med['codigo_medicamento'] ?? '';
                                            $nom = $med['nombre'] ?? '';
                                            $forma = $med['forma_farmaceutica'] ?? 'Inyectable';
                                        @endphp
                                        <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2" style="cursor: pointer;" wire:click="seleccionarMedicamentoInyectable({{ $idMed }}, '{{ addslashes($cod) }}', '{{ addslashes($nom) }}')">
                                            <div><span class="badge badge-light border text-secondary font-weight-bold mr-1">{{ $cod }}</span><span class="font-weight-bold text-dark">{{ $nom }}</span></div>
                                            <span class="badge badge-info px-2 py-1">{{ $forma }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @elseif($mostrarDropdownMedicamentos && strlen(trim($buscarMedicamento)) >= 2)
                                <div class="position-absolute w-100 bg-white border rounded p-2 text-muted shadow-sm" style="z-index: 1050; top: 100%; left: 0;"><small><i class="fas fa-info-circle mr-1"></i> No se encontraron inyectables.</small></div>
                            @endif
                        </div>
                        
                        <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                            <label class="font-weight-bold text-dark"><i class="fas fa-sort-numeric-up-alt mr-1"></i> CANTIDAD: <span style="color:#cb4335;"><b>*</b></span></label>
                            <input type="number" min="1" class="form-control input-comun @error('cantidadManual') is-invalid @enderror" wire:model="cantidadManual">
                            @error('cantidadManual') <div class="text-danger small mt-1"><b>{{ $message }}</b></div> @enderror
                        </div>
                        
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <label class="font-weight-bold text-dark"><i class="fas fa-syringe mr-1"></i> VÍA PARENTERAL: <span style="color:#cb4335;"><b>*</b></span></label>
                            <select class="form-control input-comun @error('idViaParenteralManual') is-invalid @enderror" wire:model="idViaParenteralManual">
                                <option value="">Seleccione vía...</option>
                                @foreach ($viasParenterales as $via)
                                    <option value="{{ is_array($via) ? $via['id'] : $via->id }}">{{ is_array($via) ? $via['nombre'] : $via->nombre }}</option>
                                @endforeach
                            </select>
                            @error('idViaParenteralManual') <div class="text-danger small mt-1"><b>{{ $message }}</b></div> @enderror
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-12 col-12 text-right pt-2">
                            <x-agregar-button wire:click="agregarInyeccionManual">AGREGAR</x-agregar-button>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    @endif
    
    <br>

    {{-- ========================================================== --}}
    {{-- BLOQUE 3: LISTADO DE INYECCIONES                           --}}
    {{-- ========================================================== --}}
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
            <div style="background-color: #1f618d; color: white; padding: 10px; border-radius: 5px 5px 0 0; text-align: center; font-weight: bold; font-size: 14px;">
                LISTADO DE INYECCIONES INGRESADOS
            </div>
            <div style="height: 0.5rem;"></div>
            <div style="max-height: 250px; overflow-y: auto;">
                <table class="table tabla-azul-oscuro">
                    <thead>
                        <tr>
                            <th style="width: 5%; vertical-align: middle;">#</th>
                            <th style="width: 10%; vertical-align: middle;"># RECETA</th>
                            <th style="width: 15%; vertical-align: middle;">CÓDIGO MEDICAMENTO</th>
                            <th style="width: 25%; vertical-align: middle;">VÍA PARENTERAL</th>
                            <th style="width: 35%; vertical-align: middle;">INYECCIÓN MEDICAMENTO</th>
                            <th style="width: 10%; vertical-align: middle;">OPCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($inyeccionesIngresados as $k => $item)
                            <tr>
                                <td style="vertical-align: middle;" class="text-secondary font-weight-bold">{{ $k + 1 }}</td>
                                <td style="vertical-align: middle;">
                                    <span class="badge {{ $item['nro_receta'] === 'S/N' ? 'badge-light border' : 'badge-primary' }} px-2 py-1 font-weight-bold" style="font-size: 12px;">{{ $item['nro_receta'] }}</span>
                                </td>
                                <td style="vertical-align: middle;" class="font-weight-bold text-secondary">
                                    <span class="badge badge-light border px-2 py-1">{{ $item['codigo_medicamento'] ?? '' }}</span>
                                </td>
                                <td style="vertical-align: middle;" class="font-weight-bold text-dark">{{ $item['via_parenteral_nombre'] }}</td>
                                <td style="vertical-align: middle; text-align: left; padding-left: 15px;" class="font-weight-bold text-secondary">{{ $item['medicamento_nombre'] }}</td>
                                <td style="vertical-align: middle;" class="text-center">
                                    <x-eliminar-button wire:click="quitarInyeccion({{ $k }})"></x-eliminar-button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-4 text-muted" style="background-color: #ffffff;">
                                    <i class="fas fa-info-circle text-info mr-1"></i><em>Ningún elemento seleccionado.</em>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <br>

    {{-- ========================================================== --}}
    {{-- BLOQUE 4: CHECKLIST                                        --}}
    {{-- ========================================================== --}}
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
            <fieldset style="border: 1px solid #2471a3; border-radius: 5px; padding: 15px; position: relative;">
                <legend style="color: #2471a3; font-size: 1.25rem; padding: 0; margin-left: 10px; display: inline-block; width: auto; font-weight: bold;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-check-fill" viewBox="0 0 16 16">
                        <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5z"/>
                        <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585q.084.236.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5q.001-.264.085-.5zm6.769 6.854-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708.708z"/>
                    </svg>
                    <em>Procedimientos de Enfermería Adicionales:</em>
                </legend>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <div class="custom-control custom-checkbox mb-1">
                            <input type="checkbox" class="custom-control-input" id="chk_cur_plana" wire:model.live="curacion_plana">
                            <label class="custom-control-label font-weight-bold text-secondary" for="chk_cur_plana">Curación plana</label>
                        </div>
                        @if($curacion_plana) <input type="text" class="form-control form-control-sm" placeholder="Observación..." wire:model="obs_curacion_plana"> @endif
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="custom-control custom-checkbox mb-1">
                            <input type="checkbox" class="custom-control-input" id="chk_cur_infectada" wire:model.live="curacion_infectada">
                            <label class="custom-control-label font-weight-bold text-secondary" for="chk_cur_infectada">Curación infectada</label>
                        </div>
                        @if($curacion_infectada) <input type="text" class="form-control form-control-sm" placeholder="Observación..." wire:model="obs_curacion_infectada"> @endif
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="custom-control custom-checkbox mb-1">
                            <input type="checkbox" class="custom-control-input" id="chk_oxigeno" wire:model.live="oxigenoterapia">
                            <label class="custom-control-label font-weight-bold text-secondary" for="chk_oxigeno">Oxigenoterapia</label>
                        </div>
                        @if($oxigenoterapia) <input type="text" class="form-control form-control-sm" placeholder="Observación..." wire:model="obs_oxigenoterapia"> @endif
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="custom-control custom-checkbox mb-1">
                            <input type="checkbox" class="custom-control-input" id="chk_puntos" wire:model.live="retiro_puntos">
                            <label class="custom-control-label font-weight-bold text-secondary" for="chk_puntos">Retiro puntos</label>
                        </div>
                        @if($retiro_puntos) <input type="text" class="form-control form-control-sm" placeholder="Observación..." wire:model="obs_retiro_puntos"> @endif
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="custom-control custom-checkbox mb-1">
                            <input type="checkbox" class="custom-control-input" id="chk_sensibilidad" wire:model.live="prueba_sensibilidad">
                            <label class="custom-control-label font-weight-bold text-secondary" for="chk_sensibilidad">Prueba sensibilidad</label>
                        </div>
                        @if($prueba_sensibilidad) <input type="text" class="form-control form-control-sm" placeholder="Observación..." wire:model="obs_prueba_sensibilidad"> @endif
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="custom-control custom-checkbox mb-1">
                            <input type="checkbox" class="custom-control-input" id="chk_sangria" wire:model.live="sangria">
                            <label class="custom-control-label font-weight-bold text-secondary" for="chk_sangria">Sangría</label>
                        </div>
                        @if($sangria) <input type="text" class="form-control form-control-sm" placeholder="Observación..." wire:model="obs_sangria"> @endif
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="custom-control custom-checkbox mb-1">
                            <input type="checkbox" class="custom-control-input" id="chk_vitales" wire:model.live="signos_vitales">
                            <label class="custom-control-label font-weight-bold text-secondary" for="chk_vitales">Signos vitales</label>
                        </div>
                        @if($signos_vitales) <input type="text" class="form-control form-control-sm" placeholder="Observación..." wire:model="obs_signos_vitales"> @endif
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="custom-control custom-checkbox mb-1">
                            <input type="checkbox" class="custom-control-input" id="chk_txvo" wire:model.live="tx_vo">
                            <label class="custom-control-label font-weight-bold text-secondary" for="chk_txvo">Tx VO</label>
                        </div>
                        @if($tx_vo) <input type="text" class="form-control form-control-sm" placeholder="Observación..." wire:model="obs_tx_vo"> @endif
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="custom-control custom-checkbox mb-1">
                            <input type="checkbox" class="custom-control-input" id="chk_orientacion" wire:model.live="orientacion_salud">
                            <label class="custom-control-label font-weight-bold text-secondary" for="chk_orientacion">Orientación salud</label>
                        </div>
                        @if($orientacion_salud) <input type="text" class="form-control form-control-sm" placeholder="Observación..." wire:model="obs_orientacion_salud"> @endif
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="custom-control custom-checkbox mb-1">
                            <input type="checkbox" class="custom-control-input" id="chk_vendajes" wire:model.live="vendajes">
                            <label class="custom-control-label font-weight-bold text-secondary" for="chk_vendajes">Vendajes</label>
                        </div>
                        @if($vendajes) <input type="text" class="form-control form-control-sm" placeholder="Observación..." wire:model="obs_vendajes"> @endif
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="custom-control custom-checkbox mb-1">
                            <input type="checkbox" class="custom-control-input" id="chk_nebulizacion" wire:model.live="nebulizacion">
                            <label class="custom-control-label font-weight-bold text-secondary" for="chk_nebulizacion">Nebulización</label>
                        </div>
                        @if($nebulizacion) <input type="text" class="form-control form-control-sm" placeholder="Observación..." wire:model="obs_nebulizacion"> @endif
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="custom-control custom-checkbox mb-1">
                            <input type="checkbox" class="custom-control-input" id="chk_otros" wire:model.live="otros">
                            <label class="custom-control-label font-weight-bold text-secondary" for="chk_otros">Otros</label>
                        </div>
                        @if($otros) <input type="text" class="form-control form-control-sm" placeholder="Especifique..." wire:model="obs_otros"> @endif
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
    <br>

    {{-- ========================================================== --}}
    {{-- BOTONES DE ACCIÓN INFERIORES                               --}}
    {{-- ========================================================== --}}
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="modal-footer pt-0 pb-0" style="border: none; background-color: transparent;">
                <x-cancel-button wire:click="cancelar" class="mr-2">CANCELAR</x-cancel-button>
                <x-save-button wire:click="$dispatch('confirmarGuardado')" wire:loading.attr="disabled">GUARDAR</x-save-button>
            </div>
        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- MODALES                                                    --}}
    {{-- ========================================================== --}}
    <div wire:ignore.self class="modal fade" id="modalAsegurados" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-white" style="background-color: #2471a3;">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-users mr-2"></i> LISTADO DE PACIENTES</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-search"></i></span></div>
                        <input type="text" class="form-control" placeholder="Buscar por Nombre, C.I. o Matrícula..." wire:model.live.debounce.300ms="terminoBusquedaPaciente">
                    </div>
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-hover table-striped text-center mb-0">
                            <thead style="background-color: #2471a3; color: white;">
                                <tr><th>#</th><th>C.I.</th><th>MATRÍCULA</th><th>NOMBRE COMPLETO</th><th>TIPO ASEGURADO</th><th>OPCIONES</th></tr>
                            </thead>
                            <tbody>
                                @forelse ($listaPacientesModal as $i => $pac)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td class="font-weight-bold">{{ $pac['ci'] ?? 'S/C' }}</td>
                                        <td><span class="badge badge-light border">{{ $pac['matricula'] ?? 'S/M' }}</span></td>
                                        <td class="text-left font-weight-bold">{{ $pac['nombreCompleto'] ?? $pac['nombre_completo'] ?? '' }}</td>
                                        <td><span class="badge badge-info">{{ $pac['tipoAsegurado'] ?? 'TITULAR' }}</span></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-success" wire:click="seleccionarPacienteModal({{ $pac['idPersona'] ?? $pac['id'] }}, '{{ addslashes($pac['nombreCompleto'] ?? $pac['nombre_completo'] ?? '') }}', '{{ $pac['ci'] ?? '' }}', '{{ $pac['matricula'] ?? '' }}')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="py-3 text-muted">No se encontraron pacientes coincidentes.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">CERRAR</button></div>
            </div>
        </div>
    </div>

    @if($modalBuscarPaciente)
        <div class="modal fade show" tabindex="-1" style="display: block !important; background-color: rgba(0, 0, 0, 0.6); z-index: 99999;">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content shadow-lg border-0" style="border-radius: 6px;">
                    <div class="modal-header d-flex align-items-center py-2 px-3" style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <h5 class="modal-title font-weight-bold text-dark d-flex align-items-center" style="font-size: 15px;">
                            <i class="fas fa-pencil-alt mr-2" style="color: #fd7e14;"></i> LISTADO DE PACIENTES
                        </h5>
                        <button type="button" class="close text-muted" wire:click="cerrarModalPacientes" style="outline: none; font-size: 22px;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-3" style="background-color: #ffffff;">
                        <div class="p-2 mb-2 font-weight-bold text-primary" style="background-color: #e0f2fe; border-radius: 4px; font-size: 12px; letter-spacing: 0.5px;">LISTA DE ASEGURADOS</div>
                        <livewire:enfermeria.inyectables.select-paciente-table />
                    </div>
                    <div class="modal-footer py-2 px-3 d-flex justify-content-end" style="background-color: #f8fafc; border-top: 1px solid #e2e8f0;">
                        <button type="button" class="btn btn-sm btn-outline-danger font-weight-bold d-flex align-items-center" wire:click="cerrarModalPacientes" style="border-radius: 4px; padding: 4px 14px;">
                            <i class="fas fa-times mr-1"></i> CERRAR
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- SCRIPTS --}}
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('confirmarGuardado', () => {
                Swal.fire({
                    title: '¿Guardar registro?',
                    text: "Se registrarán los datos ingresados.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, guardar',
                    cancelButtonText: 'No, cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('guardarAtencion');
                    }
                });
            });

            Livewire.on('alertaError', (data) => {
                Swal.fire({
                    title: 'Alerta!',
                    text: data.mensaje || data[0].mensaje, 
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            });

            Livewire.on('alertaExito', (data) => {
                Swal.fire({
                    title: '¡Modificación Exitosa!',
                    text: data.mensaje || data[0].mensaje,
                    icon: 'success',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#6f42c1'
                });
            });
        });
    </script>
</div>