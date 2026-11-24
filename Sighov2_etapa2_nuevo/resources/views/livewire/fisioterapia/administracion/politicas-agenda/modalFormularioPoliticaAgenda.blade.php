<x-modal-small-sin-footer id="formularioPoliticaAgenda" labelledby="formularioPoliticaAgendaLabel"
    :title="$idPoliticaAgenda ? 'EDITAR POLÍTICA AGENDA' : 'REGISTRAR POLÍTICA AGENDA'" wire:ignore.self>
    <form wire:submit="solicitarGuardar">
        <fieldset class="field_estilos">
            <legend>
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                    class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path
                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                    <path fill-rule="evenodd"
                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                </svg>
                <em>Complete la información correspondiente a la política:</em>
                <span>*</span>
            </legend>
            
            @if ($errorOperacion)<div class="alert alert-danger" role="alert">{{ $errorOperacion }}</div>@endif
            @foreach ([
                'codigo_version' => ['CÓDIGO VERSIÓN', 50], 
                'cupos_normales' => ['CUPOS NORMALES', 100], 
                'cupos_especiales' => ['CUPOS ESPECIALES', 100], 
                'max_sesiones_normales' => ['MAXIMAS SESIONES', 100]
                ] as $campo => [$etiqueta, $maximo])
                <div class="form-group">
                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                    @if ($campo === 'codigo_version')
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-hash" viewBox="0 0 16 16">
                        <path
                            d="M8.39 12.648a1 1 0 0 0-.015.18c0 .305.21.508.5.508.266 0 .492-.172.555-.477l.554-2.703h1.204c.421 0 .617-.234.617-.547 0-.312-.188-.53-.617-.53h-.985l.516-2.524h1.265c.43 0 .618-.227.618-.547 0-.313-.188-.524-.618-.524h-1.046l.476-2.304a1 1 0 0 0 .016-.164.51.51 0 0 0-.516-.516.54.54 0 0 0-.539.43l-.523 2.554H7.617l.477-2.304c.008-.04.015-.118.015-.164a.51.51 0 0 0-.523-.516.54.54 0 0 0-.531.43L6.53 5.484H5.414c-.43 0-.617.22-.617.532s.187.539.617.539h.906l-.515 2.523H4.609c-.421 0-.609.219-.609.531s.188.547.61.547h.976l-.516 2.492c-.008.04-.015.125-.015.18 0 .305.21.508.5.508.265 0 .492-.172.554-.477l.555-2.703h2.242zm-1-6.109h2.266l-.515 2.563H6.859l.532-2.563z" />
                    </svg>
                    @endif
                    @if ($campo === 'cupos_normales')
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M384 32H64C28.65 32 0 60.65 0 96v320c0 35.35 28.65 64 64 64h320c35.35 0 64-28.65 64-64V96c0-35.35-28.65-64-64-64zM128 384c-17.67 0-32-14.33-32-32s14.33-32 32-32 32 14.33 32 32-14.33 32-32 32zm0-192c-17.67 0-32-14.33-32-32s14.33-32 32-32 32 14.33 32 32-14.33 32-32 32zm96 96c-17.67 0-32-14.33-32-32s14.33-32 32-32 32 14.33 32 32-14.33 32-32 32zm96 96c-17.67 0-32-14.33-32-32s14.33-32 32-32 32 14.33 32 32-14.33 32-32 32zm0-192c-17.67 0-32-14.33-32-32s14.33-32 32-32 32 14.33 32 32-14.33 32-32 32z"/></svg>
                    @endif

                    @if ($campo === 'cupos_especiales')
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M384 32H64C28.65 32 0 60.65 0 96v320c0 35.35 28.65 64 64 64h320c35.35 0 64-28.65 64-64V96c0-35.35-28.65-64-64-64zM224 288c-17.67 0-32-14.33-32-32s14.33-32 32-32 32 14.33 32 32-14.33 32-32 32z"/></svg>
                    @endif

                    @if ($campo === 'max_sesiones_normales')
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M0 32v128h128V32H0zm120 120H8V40h112v112zm40-120v128h128V32H160zm120 120H168V40h112v112zm40-120v128h128V32H320zm120 120H328V40h112v112zM0 192v128h128V192H0zm120 120H8V200h112v112zm40-120v128h128V192H160zm120 120H168V200h112v112zm40-120v128h128V192H320zm120 120H328V200h112v112zM0 352v128h128V352H0zm120 120H8V360h112v112zm40-120v128h128V352H160zm120 120H168V360h112v112zm40-120v128h128V352H320z"/></svg>
                    @endif

                    <label for="politicaagenda-{{ $campo }}" class="mb-0">{{ $etiqueta }}: @if ($campo !== 'grupo')<span class="text-danger">*</span>@endif</label>
                    </div>
                    <input 
                    id="politicaagenda-{{ $campo }}" 
                    class="form-control input-comun @error($campo) is-invalid @enderror" 
                    @if($campo === 'codigo_version')
                    placeholder="codigo de la política Ej: POL-007"
                    @endif
                    @if($campo === 'cupos_normales')
                    placeholder="cupos normales de la política Ej: 5"
                    @endif
                    @if($campo === 'cupos_especiales')
                    placeholder="cupos especiales de la política Ej: 1"
                    @endif
                    @if($campo === 'max_sesiones_normales')
                    placeholder="máximas sesiones de la política Ej: 15"
                    @endif
                    @if ($campo === 'codigo_version')
                    value="{{ $codigo_version }}" readonly aria-readonly="true"
                    @else
                    
                    wire:model="{{ $campo }}"
                    @endif
                    maxlength="{{ $maximo }}" 
                    type="{{ ($etiqueta == 'CÓDIGO VERSIÓN') ? 'text' : 'number' }}" 
                    {!! $etiqueta !== 'CÓDIGO VERSIÓN' ? 'min="1" step="1" max="15" ': '' !!}
                    >
                    @error($campo)<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            @endforeach
        </fieldset></div>
        <div class="modal-footer border-0">
            <x-cancel-button data-dismiss="modal" wire:click="cerrar">CANCELAR</x-cancel-button>
            <x-save-button type="submit" wire:loading.attr="disabled">GUARDAR</x-save-button>
        </div>
    </form>
</x-modal-small-sin-footer>