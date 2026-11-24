<x-small-ver-modal id="verPoliticaAgenda" labelledby="verPoliticaAgendaLabel" title="DATOS DE LA POLÍTICA AGENDA" wire:ignore.self>
    <div class="card"><div class="card-body">
        <dl class="mb-0">
            <div class="d-flex flex-row justify-content-end">
                <div class="d-flex flex-row" style="gap: 10px;">
                    <dt>ESTADO:</dt>
                    <dd 
                        class="{{ ($estado) ? 'badge badge-success' : 'badge badge-danger' }}" 
                        style="font-size:100%;"
                    >
                        {{ ($estado) ? 'ACTIVA' : 'INACTIVA' }}
                    </dd>
                </div>

            </div>
            <div class="d-flex flex-column justify-content-between">
                <div class="d-flex flex-column" style="gap: 2px;">
                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-hash" viewBox="0 0 16 16">
                            <path
                                d="M8.39 12.648a1 1 0 0 0-.015.18c0 .305.21.508.5.508.266 0 .492-.172.555-.477l.554-2.703h1.204c.421 0 .617-.234.617-.547 0-.312-.188-.53-.617-.53h-.985l.516-2.524h1.265c.43 0 .618-.227.618-.547 0-.313-.188-.524-.618-.524h-1.046l.476-2.304a1 1 0 0 0 .016-.164.51.51 0 0 0-.516-.516.54.54 0 0 0-.539.43l-.523 2.554H7.617l.477-2.304c.008-.04.015-.118.015-.164a.51.51 0 0 0-.523-.516.54.54 0 0 0-.531.43L6.53 5.484H5.414c-.43 0-.617.22-.617.532s.187.539.617.539h.906l-.515 2.523H4.609c-.421 0-.609.219-.609.531s.188.547.61.547h.976l-.516 2.492c-.008.04-.015.125-.015.18 0 .305.21.508.5.508.265 0 .492-.172.554-.477l.555-2.703h2.242zm-1-6.109h2.266l-.515 2.563H6.859l.532-2.563z" />
                        </svg><dt>CÓDIGO VERSIÓN:</dt>
                    </div>
                    <p class="p-comun-disabled p-1">{{ $codigo_version }} </p>
                </div>
                <div class="d-flex flex-column" style="gap: 2px;">
                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M384 32H64C28.65 32 0 60.65 0 96v320c0 35.35 28.65 64 64 64h320c35.35 0 64-28.65 64-64V96c0-35.35-28.65-64-64-64zM128 384c-17.67 0-32-14.33-32-32s14.33-32 32-32 32 14.33 32 32-14.33 32-32 32zm0-192c-17.67 0-32-14.33-32-32s14.33-32 32-32 32 14.33 32 32-14.33 32-32 32zm96 96c-17.67 0-32-14.33-32-32s14.33-32 32-32 32 14.33 32 32-14.33 32-32 32zm96 96c-17.67 0-32-14.33-32-32s14.33-32 32-32 32 14.33 32 32-14.33 32-32 32zm0-192c-17.67 0-32-14.33-32-32s14.33-32 32-32 32 14.33 32 32-14.33 32-32 32z"/></svg>
                        <dt>CUPOS NORMALES:</dt>
                    </div>
                    <p class="p-comun-disabled p-1">{{ $cupos_normales }} </p>
                </div>
                <div class="d-flex flex-column" style="gap: 2px;">
                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M384 32H64C28.65 32 0 60.65 0 96v320c0 35.35 28.65 64 64 64h320c35.35 0 64-28.65 64-64V96c0-35.35-28.65-64-64-64zM224 288c-17.67 0-32-14.33-32-32s14.33-32 32-32 32 14.33 32 32-14.33 32-32 32z"/></svg>
                        <dt>CUPOS ESPECIALES:</dt>
                    </div>
                    <p class="p-comun-disabled p-1">{{ $cupos_especiales }} </p>
                </div>
                <div class="d-flex flex-column" style="gap: 2px;">
                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M0 32v128h128V32H0zm120 120H8V40h112v112zm40-120v128h128V32H160zm120 120H168V40h112v112zm40-120v128h128V32H320zm120 120H328V40h112v112zM0 192v128h128V192H0zm120 120H8V200h112v112zm40-120v128h128V192H160zm120 120H168V200h112v112zm40-120v128h128V192H320zm120 120H328V200h112v112zM0 352v128h128V352H0zm120 120H8V360h112v112zm40-120v128h128V352H160zm120 120H168V360h112v112zm40-120v128h128V352H320z"/></svg>
                        <dt>MAXIMAS SESIONES:</dt>
                    </div>
                    <p class="p-comun-disabled p-1">{{ $max_sesiones_normales }} </p>
                </div>
            </div>
            
        </dl>
    </div>
</div>
</x-small-ver-modal>