<x-small-ver-modal id="fichaFisioterapeuta" labelledby="tituloFichaFisioterapeuta" title="VER DATOS DEL FISIOTERAPEUTA" size="modal-xl" closeLabel="CERRAR" wire:ignore.self>
    @if ($detalle)
        <div class="row">
            <div class="col-md-3 text-center">
                <i class="fas fa-user-md fa-4x text-secondary" aria-hidden="true"></i>
                <p class="mt-2 {{ match ($detalle['estado']) {true => 'text-green', false => 'text-red'} }}">
                    {{ match ($detalle['estado']) {true => 'ACTIVO', false => 'DE BAJA', default => 'SIN ESTADO'} }}
                </p>
                <strong>{{ $detalle['tipoContrato'] ?? 'Sin contrato informado' }}</strong>
            </div>
            <dl class="col-md-9 row">
                
                <div class="col-sm-6">
                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M64.1 128C64.1 92.7 92.8 64 128.1 64L277.6 64C294.6 64 310.9 70.7 322.9 82.7L429.3 189.3C441.3 201.3 448 217.6 448 234.6L448 332.1L316 464.1L273.9 464.1L257.8 410.5C253.1 394.8 238.7 384.1 222.3 384.1C211 384.1 200.4 389.2 193.4 398L133.3 473C125 483.3 126.7 498.5 137 506.7C147.3 514.9 162.5 513.3 170.7 502.9L217.8 444.1L233 494.8C236 505 245.4 511.9 256 511.9L287.5 511.9C286.6 515 285.8 518.2 285.2 521.4L274.3 575.9L128.1 575.9C92.8 575.9 64.1 547.2 64.1 511.9L64.1 127.9zM272.1 122.5L272.1 216C272.1 229.3 282.8 240 296.1 240L389.6 240L272.1 122.5zM332.3 530.9C334.8 518.5 340.9 507.1 349.8 498.2L468.7 379.3L548.7 459.3L429.8 578.2C420.9 587.1 409.5 593.2 397.1 595.7L337.5 607.6C336.6 607.8 335.6 607.9 334.6 607.9C326.6 607.9 320 601.4 320 593.3C320 592.3 320.1 591.4 320.3 590.4L332.2 530.8zM600.1 407.9L571.3 436.7L491.3 356.7L520.1 327.9C542.2 305.8 578 305.8 600.1 327.9C622.2 350 622.2 385.8 600.1 407.9z"/></svg>
                        <dt>NOMBRE COMPLETO:</dt>
                    </div>
                    <p class="p-comun-disabled p-1">{{ $detalle['nombreCompleto'] ?? '—' }}</p>
                </div>
                <div class="col-sm-6">
                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M528 32H48C21.5 32 0 53.5 0 80v16h576V80c0-26.5-21.5-48-48-48zM0 432c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V128H0v304zm352-232c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H360c-4.4 0-8-3.6-8-8v-16zm0 64c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H360c-4.4 0-8-3.6-8-8v-16zm0 64c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H360c-4.4 0-8-3.6-8-8v-16zM176 192c35.3 0 64 28.7 64 64s-28.7 64-64 64-64-28.7-64-64 28.7-64 64-64zM67.1 396.2C75.5 370.5 99.6 352 128 352h8.2c12.3 5.1 25.7 8 39.8 8s27.6-2.9 39.8-8h8.2c28.4 0 52.5 18.5 60.9 44.2 3.2 9.9-5.2 19.8-15.6 19.8H82.7c-10.4 0-18.8-10-15.6-19.8z"/></svg>
                        <dt>MATRÍCULA:</dt>
                    </div>
                    <p class="p-comun-disabled p-1">{{ $detalle['matricula'] ?? '—' }}</p>
                </div>
                <div class="col-sm-6">
                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M528 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zm-352 96c35.3 0 64 28.7 64 64s-28.7 64-64 64-64-28.7-64-64 28.7-64 64-64zm112 236.8c0 10.6-10 19.2-22.4 19.2H86.4C74 384 64 375.4 64 364.8v-19.2c0-31.8 30.1-57.6 67.2-57.6h5c12.3 5.1 25.7 8 39.8 8s27.6-2.9 39.8-8h5c37.1 0 67.2 25.8 67.2 57.6v19.2zM512 312c0 4.4-3.6 8-8 8H360c-4.4 0-8-3.6-8-8v-16c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16zm0-64c0 4.4-3.6 8-8 8H360c-4.4 0-8-3.6-8-8v-16c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16zm0-64c0 4.4-3.6 8-8 8H360c-4.4 0-8-3.6-8-8v-16c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16z"/></svg>
                        <dt>CARNET:</dt>
                    </div>
                    <p class="p-comun-disabled p-1">{{ $detalle['carnet'] ?? '—' }}</p>
                </div>
                <div class="col-sm-6">
                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M172.268 501.67C26.97 291.031 0 269.413 0 192 0 85.961 85.961 0 192 0s192 85.961 192 192c0 77.413-26.97 99.031-172.268 309.67-9.535 13.774-29.93 13.773-39.464 0zM192 272c44.183 0 80-35.817 80-80s-35.817-80-80-80-80 35.817-80 80 35.817 80 80 80z"/></svg>
                        <dt>EXPEDIDO:</dt>
                    </div>
                    <p class="p-comun-disabled p-1">{{ $detalle['expedido']['sigla'] ?? '—' }}</p>
                </div>
                <div class="col-sm-6">
                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M129.62 176h39.09c1.49-27.03 6.54-51.35 14.21-70.41-27.71 13.24-48.02 39.19-53.3 70.41zm0 32c5.29 31.22 25.59 57.17 53.3 70.41-7.68-19.06-12.72-43.38-14.21-70.41h-39.09zM224 286.69c7.69-7.45 20.77-34.42 23.43-78.69h-46.87c2.67 44.26 15.75 71.24 23.44 78.69zM200.57 176h46.87c-2.66-44.26-15.74-71.24-23.43-78.69-7.7 7.45-20.78 34.43-23.44 78.69zm64.51 102.41c27.71-13.24 48.02-39.19 53.3-70.41h-39.09c-1.49 27.03-6.53 51.35-14.21 70.41zM416 0H64C28.65 0 0 28.65 0 64v384c0 35.35 28.65 64 64 64h352c17.67 0 32-14.33 32-32V32c0-17.67-14.33-32-32-32zm-80 416H112c-8.8 0-16-7.2-16-16s7.2-16 16-16h224c8.8 0 16 7.2 16 16s-7.2 16-16 16zm-112-96c-70.69 0-128-57.31-128-128S153.31 64 224 64s128 57.31 128 128-57.31 128-128 128zm41.08-214.41c7.68 19.06 12.72 43.38 14.21 70.41h39.09c-5.28-31.22-25.59-57.17-53.3-70.41z"/></svg>
                        <dt>TIPO DE DOCUMENTO:</dt>
                    </div>
                    <p class="p-comun-disabled p-1">{{ $detalle['tipoDocumento'] ?? '—' }}</p>
                </div>
                <div class="col-sm-6">
                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"  width="16" height="16"><path fill="rgb(0, 0, 0)" d="M436 160H12c-6.627 0-12-5.373-12-12v-36c0-26.51 21.49-48 48-48h48V12c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v52h128V12c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v52h48c26.51 0 48 21.49 48 48v36c0 6.627-5.373 12-12 12zM12 192h424c6.627 0 12 5.373 12 12v260c0 26.51-21.49 48-48 48H48c-26.51 0-48-21.49-48-48V204c0-6.627 5.373-12 12-12zm333.296 95.947l-28.169-28.398c-4.667-4.705-12.265-4.736-16.97-.068L194.12 364.665l-45.98-46.352c-4.667-4.705-12.266-4.736-16.971-.068l-28.397 28.17c-4.705 4.667-4.736 12.265-.068 16.97l82.601 83.269c4.667 4.705 12.265 4.736 16.97.068l142.953-141.805c4.705-4.667 4.736-12.265.068-16.97z"/></svg>
                        <dt>FECHA DE NACIMIENTO:</dt>
                    </div>
                    <p class="p-comun-disabled p-1">{{ $detalle['fechaNacimiento'] ?? '—' }}</p>
                </div>
                <div class="col-sm-6">
                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M319.4 320.6L224 416l-95.4-95.4C57.1 323.7 0 382.2 0 454.4v9.6c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-9.6c0-72.2-57.1-130.7-128.6-133.8zM13.6 79.8l6.4 1.5v58.4c-7 4.2-12 11.5-12 20.3 0 8.4 4.6 15.4 11.1 19.7L3.5 242c-1.7 6.9 2.1 14 7.6 14h41.8c5.5 0 9.3-7.1 7.6-14l-15.6-62.3C51.4 175.4 56 168.4 56 160c0-8.8-5-16.1-12-20.3V87.1l66 15.9c-8.6 17.2-14 36.4-14 57 0 70.7 57.3 128 128 128s128-57.3 128-128c0-20.6-5.3-39.8-14-57l96.3-23.2c18.2-4.4 18.2-27.1 0-31.5l-190.4-46c-13-3.1-26.7-3.1-39.7 0L13.6 48.2c-18.1 4.4-18.1 27.2 0 31.6z"/></svg>
                        <dt>GRADO ACADÉMICO:</dt>
                    </div>
                    <p class="p-comun-disabled p-1">{{ $detalle['gradoAcademico'] ?? '—' }}</p>
                </div>
                <div class="col-sm-6">
                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M336 0H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48V48c0-26.5-21.5-48-48-48zM144 32h96c8.8 0 16 7.2 16 16s-7.2 16-16 16h-96c-8.8 0-16-7.2-16-16s7.2-16 16-16zm48 128c35.3 0 64 28.7 64 64s-28.7 64-64 64-64-28.7-64-64 28.7-64 64-64zm112 236.8c0 10.6-10 19.2-22.4 19.2H102.4C90 416 80 407.4 80 396.8v-19.2c0-31.8 30.1-57.6 67.2-57.6h5c12.3 5.1 25.7 8 39.8 8s27.6-2.9 39.8-8h5c37.1 0 67.2 25.8 67.2 57.6v19.2z"/></svg>
                        <dt>ESPECIALIDAD:</dt>
                    </div>
                    <p class="p-comun-disabled p-1">{{ $detalle['especialidad']['nombre'] ?? '—' }}</p>
                </div>
            </dl>
        </div>
        <div class="flex-row border-top" style="display:flex; align-items: center; gap: 5px; margin-bottom: 5px; padding-top: 5px;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M0 464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V192H0v272zm320-196c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zM192 268c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zM64 268c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zM400 64h-48V16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v48H160V16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v48H48C21.5 64 0 85.5 0 112v48h448v-48c0-26.5-21.5-48-48-48z"/></svg>
            <h6 class="mb-0">HORARIOS SEMANALES HABILITADOS</h6>
        </div>
        @if ($horarios === null)
            <p class="alert alert-warning">No se pudieron consultar los horarios.</p>
        @else
            @php
                $dias = [1 => 'LUNES', 2 => 'MARTES', 3 => 'MIÉRCOLES', 4 => 'JUEVES', 5 => 'VIERNES'];
                $porDia = collect($horarios)->groupBy('diaSemana');
            @endphp
            <div class="table-responsive">
                <table class="table tabla-celeste" aria-label="Horarios semanales habilitados">
                    <thead>
                        <tr>
                            @foreach ($dias as $nombreDia)
                                <th scope="col" style="width:20%">{{ $nombreDia }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach ($dias as $numero => $nombreDia)
                                <td class="align-top p-2">
                                    @php($finAnterior = null)
                                    @forelse ($porDia->get($numero, []) as $turno)
                                        <div class="border border-success rounded mb-0 p-1">
                                            <div class="flex-row" style="display:flex; gap: 5px; justify-content: flex-start; align-items: center;">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="12" height="12"><path fill="rgb(0, 0, 0)" d="M360 0H24C10.745 0 0 10.745 0 24v16c0 13.255 10.745 24 24 24 0 90.965 51.016 167.734 120.842 192C75.016 280.266 24 357.035 24 448c-13.255 0-24 10.745-24 24v16c0 13.255 10.745 24 24 24h336c13.255 0 24-10.745 24-24v-16c0-13.255-10.745-24-24-24 0-90.965-51.016-167.734-120.842-192C308.984 231.734 360 154.965 360 64c13.255 0 24-10.745 24-24V24c0-13.255-10.745-24-24-24zm-64 448H88c0-77.458 46.204-144 104-144 57.786 0 104 66.517 104 144z"/></svg>
                                                <strong class="text-nowrap" style="font-size: 12px; ">{{ substr($turno['horaInicio'], 0, 5) }} – {{ substr($turno['horaFin'], 0, 5) }}</strong>
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="12" height="12"><path fill="rgb(0, 0, 0)" d="M360 64c13.255 0 24-10.745 24-24V24c0-13.255-10.745-24-24-24H24C10.745 0 0 10.745 0 24v16c0 13.255 10.745 24 24 24 0 90.965 51.016 167.734 120.842 192C75.016 280.266 24 357.035 24 448c-13.255 0-24 10.745-24 24v16c0 13.255 10.745 24 24 24h336c13.255 0 24-10.745 24-24v-16c0-13.255-10.745-24-24-24 0-90.965-51.016-167.734-120.842-192C308.984 231.734 360 154.965 360 64zM192 208c-57.787 0-104-66.518-104-144h208c0 77.945-46.51 144-104 144z"/></svg>
                                            </div>
                                            
                                            <div class="flex-row" style="display:flex; gap: 5px; justify-content: flex-start; align-items: center;">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="12" height="12"><path fill="rgb(0, 0, 0)" d="M384 32H64C28.65 32 0 60.65 0 96v320c0 35.35 28.65 64 64 64h320c35.35 0 64-28.65 64-64V96c0-35.35-28.65-64-64-64zM128 384c-17.67 0-32-14.33-32-32s14.33-32 32-32 32 14.33 32 32-14.33 32-32 32zm0-192c-17.67 0-32-14.33-32-32s14.33-32 32-32 32 14.33 32 32-14.33 32-32 32zm96 96c-17.67 0-32-14.33-32-32s14.33-32 32-32 32 14.33 32 32-14.33 32-32 32zm96 96c-17.67 0-32-14.33-32-32s14.33-32 32-32 32 14.33 32 32-14.33 32-32 32zm0-192c-17.67 0-32-14.33-32-32s14.33-32 32-32 32 14.33 32 32-14.33 32-32 32z"/></svg>
                                                <p style="font-size: 12px; margin-bottom: 0px; white-space: nowrap !important;">CUPOS NORMALES: {{ $turno['cuposNormales'] ?? '—' }}</p>
                                            </div>
                                            <div class="flex-row" style="display:flex; gap: 5px; justify-content: flex-start; align-items: center;">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="12" height="12"><path fill="rgb(0, 0, 0)" d="M384 32H64C28.65 32 0 60.65 0 96v320c0 35.35 28.65 64 64 64h320c35.35 0 64-28.65 64-64V96c0-35.35-28.65-64-64-64zM224 288c-17.67 0-32-14.33-32-32s14.33-32 32-32 32 14.33 32 32-14.33 32-32 32z"/></svg>
                                                <p style="font-size: 12px; margin-bottom: 0px; white-space: nowrap !important;">CUPOS ESPECIALES: {{ $turno['cuposEspeciales'] ?? '—' }}</p> 
                                            </div>
                                            <div class="flex-row" style="display:flex; gap: 5px; justify-content: flex-start; align-items: center;">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="12" height="12"><path fill="rgb(0, 0, 0)" d="M544 96H416V32c0-17.7-14.3-32-32-32H192c-17.7 0-32 14.3-32 32v64H32c-17.7 0-32 14.3-32 32v368c0 8.8 7.2 16 16 16h544c8.8 0 16-7.2 16-16V128c0-17.7-14.3-32-32-32zM160 436c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40zm0-128c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40zm160 128c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40zm0-128c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40zm16-170c0 3.3-2.7 6-6 6h-26v26c0 3.3-2.7 6-6 6h-20c-3.3 0-6-2.7-6-6v-26h-26c-3.3 0-6-2.7-6-6v-20c0-3.3 2.7-6 6-6h26V86c0-3.3 2.7-6 6-6h20c3.3 0 6 2.7 6 6v26h26c3.3 0 6 2.7 6 6v20zm144 298c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40zm0-128c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40z"/></svg>    
                                                <p style="font-size: 12px; margin-bottom: 0px; white-space: nowrap !important;">UBICACIÓN: {{ collect($turno['sede'])->filter(fn ($v) => filled($v))->implode(', ') ?: 'Sin ubicación registrada' }}</p>
                                            </div>
                                            @if ($finAnterior !== null && $turno['horaInicio'] < $finAnterior)
                                                <small class="text-danger">Se superpone con otro turno del día.</small>
                                            @endif
                                        </div>
                                        @php($finAnterior = max($finAnterior, $turno['horaFin']))
                                    @empty
                                        <span class="text-muted">SIN HORARIO</span>
                                    @endforelse
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
            @if ($porDia->except(array_keys($dias))->isNotEmpty())
                <p class="alert alert-warning mt-2">Hay horarios registrados sin un día válido de lunes a viernes. Revise su asignación.</p>
            @endif
        @endif
    @endif
</x-small-ver-modal>
