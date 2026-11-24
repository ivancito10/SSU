<x-modal-small-sin-footer id="formularioHorario" labelledby="formularioHorarioLabel" title="REGISTRO DE HORARIO" wire:ignore.self>
    <form wire:submit="solicitarHorario">
        <fieldset class="field_estilos">
            <legend>
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                    class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path
                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                    <path fill-rule="evenodd"
                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                </svg>
                <em style="font-size: 20px;">Horario semanal de {{ $nombreHorario }}:</em>
                <span>*</span>
            </legend>
            
            @error('turno.operacion') 
            <div class="alert alert-danger">{{ $message }}</div> 
            @enderror
            <div class="form-group">
                <label for="turno-dia">
                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M0 464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V192H0v272zm64-192c0-8.8 7.2-16 16-16h96c8.8 0 16 7.2 16 16v96c0 8.8-7.2 16-16 16H80c-8.8 0-16-7.2-16-16v-96zM400 64h-48V16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v48H160V16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v48H48C21.5 64 0 85.5 0 112v48h448v-48c0-26.5-21.5-48-48-48z"/></svg>
                        <div>
                            <p class="mb-0">DÍA: <span class="text-red"><span class="text-red">*</span></p>
                        </div>
                    </div>
                </label>
                <select id="turno-dia" wire:model="turno.dia"
                    class="form-control input-comun @error('turno.dia') is-invalid @enderror">
                    <option value="">Seleccione</option>
                    <option value="1">Lunes</option>
                    <option value="2">Martes</option>
                    <option value="3">Miércoles</option>
                    <option value="4">Jueves</option>
                    <option value="5">Viernes</option>
                </select>
                @error('turno.dia')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                
                <label for="turno-intervalo">
                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M256,8C119,8,8,119,8,256S119,504,256,504,504,393,504,256,393,8,256,8Zm92.49,313h0l-20,25a16,16,0,0,1-22.49,2.5h0l-67-49.72a40,40,0,0,1-15-31.23V112a16,16,0,0,1,16-16h32a16,16,0,0,1,16,16V256l58,42.5A16,16,0,0,1,348.49,321Z"/></svg>
                        <div>
                            <p class="mb-0">INTERVALO (MINUTOS): <span class="text-red"><span class="text-red">*</span></p>
                        </div>
                    </div>
                </label>
                <input type="number" id="turno-intervalo" wire:model="turno.intervalo"
                    class="form-control @error('turno.intervalo') is-invalid @enderror">
                @error('turno.intervalo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="turno-desde">
                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M360 0H24C10.745 0 0 10.745 0 24v16c0 13.255 10.745 24 24 24 0 90.965 51.016 167.734 120.842 192C75.016 280.266 24 357.035 24 448c-13.255 0-24 10.745-24 24v16c0 13.255 10.745 24 24 24h336c13.255 0 24-10.745 24-24v-16c0-13.255-10.745-24-24-24 0-90.965-51.016-167.734-120.842-192C308.984 231.734 360 154.965 360 64c13.255 0 24-10.745 24-24V24c0-13.255-10.745-24-24-24zm-64 448H88c0-77.458 46.204-144 104-144 57.786 0 104 66.517 104 144z"/></svg>
                        <div>
                            <p class="mb-0">HORA INICIAL: <span class="text-red"><span class="text-red">*</span></p>
                        </div>
                    </div>
                </label>
                <input type="time" id="turno-desde" wire:model="turno.desde"
                    class="form-control @error('turno.desde') is-invalid @enderror">
                @error('turno.desde')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="turno-hasta">
                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M360 64c13.255 0 24-10.745 24-24V24c0-13.255-10.745-24-24-24H24C10.745 0 0 10.745 0 24v16c0 13.255 10.745 24 24 24 0 90.965 51.016 167.734 120.842 192C75.016 280.266 24 357.035 24 448c-13.255 0-24 10.745-24 24v16c0 13.255 10.745 24 24 24h336c13.255 0 24-10.745 24-24v-16c0-13.255-10.745-24-24-24 0-90.965-51.016-167.734-120.842-192C308.984 231.734 360 154.965 360 64zM192 208c-57.787 0-104-66.518-104-144h208c0 77.945-46.51 144-104 144z"/></svg>
                        <div>
                            <p class="mb-0">HORA FINAL: <span class="text-red"><span class="text-red">*</span></p>
                        </div>
                    </div>
                </label>
                <input type="time" id="turno-hasta" wire:model="turno.hasta"
                    class="form-control @error('turno.hasta') is-invalid @enderror">
                @error('turno.hasta')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="turno-sede">
                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M544 96H416V32c0-17.7-14.3-32-32-32H192c-17.7 0-32 14.3-32 32v64H32c-17.7 0-32 14.3-32 32v368c0 8.8 7.2 16 16 16h544c8.8 0 16-7.2 16-16V128c0-17.7-14.3-32-32-32zM160 436c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40zm0-128c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40zm160 128c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40zm0-128c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40zm16-170c0 3.3-2.7 6-6 6h-26v26c0 3.3-2.7 6-6 6h-20c-3.3 0-6-2.7-6-6v-26h-26c-3.3 0-6-2.7-6-6v-20c0-3.3 2.7-6 6-6h26V86c0-3.3 2.7-6 6-6h20c3.3 0 6 2.7 6 6v26h26c3.3 0 6 2.7 6 6v20zm144 298c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40zm0-128c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40z"/></svg>
                        <div>
                            <p class="mb-0">SEDE - UBICACIÓN: <span class="text-red"><span class="text-red">*</span></p>
                        </div>
                    </div>
                </label>
                <select id="turno-sede" wire:model="turno.sede"
                    class="form-control input-comun @error('turno.sede') is-invalid @enderror">
                    <option value="">Seleccione</option>
                    @foreach ($sedesHorario as $id => $nombre)
                        <option value="{{ $id }}">{{ $nombre }}</option>
                    @endforeach
                </select>
                @error('turno.sede')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="turno-politica">
                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M0 464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V192H0v272zm320-196c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zM192 268c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zM64 268c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zM400 64h-48V16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v48H160V16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v48H48C21.5 64 0 85.5 0 112v48h448v-48c0-26.5-21.5-48-48-48z"/></svg>
                        <div>
                            <p class="mb-0">POLÍTICA DE AGENDA: <span class="text-red"><span class="text-red">*</span></p>
                        </div>
                    </div>
                </label>
                <select id="turno-politica" wire:model="turno.politica"
                    class="form-control input-comun @error('turno.politica') is-invalid @enderror">
                    <option value="">Seleccione</option>
                    @foreach ($politicasHorario as $id => $nombre)
                        <option value="{{ $id }}">{{ $nombre }}</option>
                    @endforeach
                </select>
                @error('turno.politica')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            @if ($idHorarioEspecialista && !$politicasHorario)
            <p class="text-warning">Debe habilitar una política de agenda vigente para registrar horarios.</p>
            @endif
        </fieldset>
        <div class="modal-footer border-0">
            <x-cancel-button data-dismiss="modal">CANCELAR</x-cancel-button>
            <x-save-button type="submit" wire:loading.attr="disabled">GUARDAR</x-save-button>
        </div>
    </form>
</x-modal-small-sin-footer>
