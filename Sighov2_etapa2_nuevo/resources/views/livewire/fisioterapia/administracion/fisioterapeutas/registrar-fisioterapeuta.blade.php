<div>
    <div style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e; padding:4px; border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">
                REGISTRAR NUEVO FISIOTERAPEUTA
            </h6>
        </div>
        <div style="margin-left: auto; padding-right:10px; position: relative;">
            <x-atras-button wire:click="cancelarRegistro" wire:loading.attr="disabled" wire:target="cancelarRegistro">
                ATRÁS
            </x-atras-button>
            <div wire:loading wire:target="cancelarRegistro">
                <x-cargando-dos />
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h6 class="text-center py-2 small font-weight-bold" style="background:#e7f0f7">DATOS PERSONALES DE FISIOTERAPEUTA</h6>
            <form wire:submit="buscar" class="form-inline mb-2">
                <button class="btn btn-primary btn-sm mr-2" aria-label="Buscar persona" wire:loading.attr="disabled">
                    <i class="fas fa-search"></i>
                </button>
                <input aria-label="C.I." placeholder="C.I." class="form-control form-control-sm mr-2 input-comun @error('ci') is-invalid @enderror" wire:model="ci" @error('ci') aria-invalid="true" title="{{ $message }}" @enderror maxlength="30" required>
                <input aria-label="Complemento" placeholder="COMPLEMENTO" class="form-control form-control-sm input-comun @error('complemento') is-invalid @enderror" wire:model="complemento" @error('complemento') aria-invalid="true" title="{{ $message }}" @enderror maxlength="10">
            </form>
            @error('registro')
                <p class="alert alert-warning" role="alert">{{ $message }}</p>
            @enderror
            @if ($documento)
            <p class="badge {{ $idPersona ? 'badge-success' : 'badge-danger' }}">
                {{ $idPersona ? 'Persona encontrada. Verifique sus datos.' : 'Persona no registrada. Ingrese los datos de manera manual.' }}
            </p>
            <fieldset class="field_estilos">
                <legend>
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                        class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path
                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                        <path fill-rule="evenodd"
                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                    </svg>
                    <em>Complete la información correspondiente del fisioterapeuta:</em>
                    <span>*</span>
                </legend>
                <!-- <div class="text-right text-primary mb-2">
                    <i class="far fa-edit"></i> 
                    Completar los datos correspondientes
                </div> -->
                <div class="row">
                    <div class="col-md-3">
                        <label class="small">
                            <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M512 144v288c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V144c0-26.5 21.5-48 48-48h88l12.3-32.9c7-18.7 24.9-31.1 44.9-31.1h125.5c20 0 37.9 12.4 44.9 31.1L376 96h88c26.5 0 48 21.5 48 48zM376 288c0-66.2-53.8-120-120-120s-120 53.8-120 120 53.8 120 120 120 120-53.8 120-120zm-32 0c0 48.5-39.5 88-88 88s-88-39.5-88-88 39.5-88 88-88 88 39.5 88 88z"/></svg>
                                <div>
                                    <p class="mb-0">SELECCIONAR FOTOGRAFÍA:</p>
                                </div>  
                            </div>
                            <input type="file" disabled class="form-control-file input-comun"><small>Carga de fotografía pendiente.</small>
                        </label>
                    </div>
                    <div class="col-md-9">
                        <div class="form-row">
                            <div class="col-sm">
                                <label class="d-block small font-weight-bold">
                                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M64.1 128C64.1 92.7 92.8 64 128.1 64L277.6 64C294.6 64 310.9 70.7 322.9 82.7L429.3 189.3C441.3 201.3 448 217.6 448 234.6L448 332.1L316 464.1L273.9 464.1L257.8 410.5C253.1 394.8 238.7 384.1 222.3 384.1C211 384.1 200.4 389.2 193.4 398L133.3 473C125 483.3 126.7 498.5 137 506.7C147.3 514.9 162.5 513.3 170.7 502.9L217.8 444.1L233 494.8C236 505 245.4 511.9 256 511.9L287.5 511.9C286.6 515 285.8 518.2 285.2 521.4L274.3 575.9L128.1 575.9C92.8 575.9 64.1 547.2 64.1 511.9L64.1 127.9zM272.1 122.5L272.1 216C272.1 229.3 282.8 240 296.1 240L389.6 240L272.1 122.5zM332.3 530.9C334.8 518.5 340.9 507.1 349.8 498.2L468.7 379.3L548.7 459.3L429.8 578.2C420.9 587.1 409.5 593.2 397.1 595.7L337.5 607.6C336.6 607.8 335.6 607.9 334.6 607.9C326.6 607.9 320 601.4 320 593.3C320 592.3 320.1 591.4 320.3 590.4L332.2 530.8zM600.1 407.9L571.3 436.7L491.3 356.7L520.1 327.9C542.2 305.8 578 305.8 600.1 327.9C622.2 350 622.2 385.8 600.1 407.9z"/></svg>
                                        <div>
                                            <p class="mb-0">NOMBRES: <span class="text-red"><span class="text-red">*</span></p>
                                        </div>  
                                    </div>
                                    <input class="input-comun form-control form-control-sm mt-1 @error('f.nombres') is-invalid @enderror" type="text" wire:model.blur="f.nombres" @error('f.nombres') aria-invalid="true" title="{{ $message }}" @enderror >
                                </label>
                            </div>
                            <div class="col-sm">
                                <label class="d-block small font-weight-bold">
                                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M64.1 128C64.1 92.7 92.8 64 128.1 64L277.6 64C294.6 64 310.9 70.7 322.9 82.7L429.3 189.3C441.3 201.3 448 217.6 448 234.6L448 332.1L316 464.1L273.9 464.1L257.8 410.5C253.1 394.8 238.7 384.1 222.3 384.1C211 384.1 200.4 389.2 193.4 398L133.3 473C125 483.3 126.7 498.5 137 506.7C147.3 514.9 162.5 513.3 170.7 502.9L217.8 444.1L233 494.8C236 505 245.4 511.9 256 511.9L287.5 511.9C286.6 515 285.8 518.2 285.2 521.4L274.3 575.9L128.1 575.9C92.8 575.9 64.1 547.2 64.1 511.9L64.1 127.9zM272.1 122.5L272.1 216C272.1 229.3 282.8 240 296.1 240L389.6 240L272.1 122.5zM332.3 530.9C334.8 518.5 340.9 507.1 349.8 498.2L468.7 379.3L548.7 459.3L429.8 578.2C420.9 587.1 409.5 593.2 397.1 595.7L337.5 607.6C336.6 607.8 335.6 607.9 334.6 607.9C326.6 607.9 320 601.4 320 593.3C320 592.3 320.1 591.4 320.3 590.4L332.2 530.8zM600.1 407.9L571.3 436.7L491.3 356.7L520.1 327.9C542.2 305.8 578 305.8 600.1 327.9C622.2 350 622.2 385.8 600.1 407.9z"/></svg>
                                        <div>
                                            <p class="mb-0">PRIMER APELLIDO: <span class="text-red"><span class="text-red">*</span></p>
                                        </div>
                                    </div>
                                    <input class="input-comun form-control form-control-sm mt-1 @error('f.primerApellido') is-invalid @enderror" type="text" wire:model.blur="f.primerApellido" @error('f.primerApellido') aria-invalid="true" title="{{ $message }}" @enderror >
                                </label>
                            </div>
                            <div class="col-sm">
                                <label class="d-block small font-weight-bold">
                                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M64.1 128C64.1 92.7 92.8 64 128.1 64L277.6 64C294.6 64 310.9 70.7 322.9 82.7L429.3 189.3C441.3 201.3 448 217.6 448 234.6L448 332.1L316 464.1L273.9 464.1L257.8 410.5C253.1 394.8 238.7 384.1 222.3 384.1C211 384.1 200.4 389.2 193.4 398L133.3 473C125 483.3 126.7 498.5 137 506.7C147.3 514.9 162.5 513.3 170.7 502.9L217.8 444.1L233 494.8C236 505 245.4 511.9 256 511.9L287.5 511.9C286.6 515 285.8 518.2 285.2 521.4L274.3 575.9L128.1 575.9C92.8 575.9 64.1 547.2 64.1 511.9L64.1 127.9zM272.1 122.5L272.1 216C272.1 229.3 282.8 240 296.1 240L389.6 240L272.1 122.5zM332.3 530.9C334.8 518.5 340.9 507.1 349.8 498.2L468.7 379.3L548.7 459.3L429.8 578.2C420.9 587.1 409.5 593.2 397.1 595.7L337.5 607.6C336.6 607.8 335.6 607.9 334.6 607.9C326.6 607.9 320 601.4 320 593.3C320 592.3 320.1 591.4 320.3 590.4L332.2 530.8zM600.1 407.9L571.3 436.7L491.3 356.7L520.1 327.9C542.2 305.8 578 305.8 600.1 327.9C622.2 350 622.2 385.8 600.1 407.9z"/></svg>
                                        <div>
                                            <p class="mb-0">SEGUNDO APELLIDO: </p>
                                        </div>
                                    </div>
                                    <input class="input-comun form-control form-control-sm mt-1 @error('f.segundoApellido') is-invalid @enderror" type="text" wire:model.blur="f.segundoApellido" @error('f.segundoApellido') aria-invalid="true" title="{{ $message }}" @enderror >
                                </label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-sm">
                                <label class="d-block small font-weight-bold">
                                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M528 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zm-352 96c35.3 0 64 28.7 64 64s-28.7 64-64 64-64-28.7-64-64 28.7-64 64-64zm112 236.8c0 10.6-10 19.2-22.4 19.2H86.4C74 384 64 375.4 64 364.8v-19.2c0-31.8 30.1-57.6 67.2-57.6h5c12.3 5.1 25.7 8 39.8 8s27.6-2.9 39.8-8h5c37.1 0 67.2 25.8 67.2 57.6v19.2zM512 312c0 4.4-3.6 8-8 8H360c-4.4 0-8-3.6-8-8v-16c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16zm0-64c0 4.4-3.6 8-8 8H360c-4.4 0-8-3.6-8-8v-16c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16zm0-64c0 4.4-3.6 8-8 8H360c-4.4 0-8-3.6-8-8v-16c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16z"/></svg>
                                        <div>
                                            <p class="mb-0">CARNET: <span class="text-red"><span class="text-red">*</span></p>
                                        </div>
                                    </div>
                                    <input class="input-comun form-control form-control-sm mt-1" readonly value="{{ $documento['ci'] }}">
                                </label>
                            </div>
                            <div class="col-sm">
                                <label class="d-block small font-weight-bold">
                                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M528 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zm-352 96c35.3 0 64 28.7 64 64s-28.7 64-64 64-64-28.7-64-64 28.7-64 64-64zm112 236.8c0 10.6-10 19.2-22.4 19.2H86.4C74 384 64 375.4 64 364.8v-19.2c0-31.8 30.1-57.6 67.2-57.6h5c12.3 5.1 25.7 8 39.8 8s27.6-2.9 39.8-8h5c37.1 0 67.2 25.8 67.2 57.6v19.2zM512 312c0 4.4-3.6 8-8 8H360c-4.4 0-8-3.6-8-8v-16c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16zm0-64c0 4.4-3.6 8-8 8H360c-4.4 0-8-3.6-8-8v-16c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16zm0-64c0 4.4-3.6 8-8 8H360c-4.4 0-8-3.6-8-8v-16c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16z"/></svg>
                                        <div>
                                            <p class="mb-0">COMPLEMENTO: <span class="text-red"><span class="text-red">*</span></p>
                                        </div>
                                    </div>
                                    <input class="input-comun form-control form-control-sm mt-1" readonly value="{{ $documento['complemento'] }}">
                                </label>
                            </div>
                            <div class="col-sm">
                                <label class="d-block small font-weight-bold">
                                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M129.62 176h39.09c1.49-27.03 6.54-51.35 14.21-70.41-27.71 13.24-48.02 39.19-53.3 70.41zm0 32c5.29 31.22 25.59 57.17 53.3 70.41-7.68-19.06-12.72-43.38-14.21-70.41h-39.09zM224 286.69c7.69-7.45 20.77-34.42 23.43-78.69h-46.87c2.67 44.26 15.75 71.24 23.44 78.69zM200.57 176h46.87c-2.66-44.26-15.74-71.24-23.43-78.69-7.7 7.45-20.78 34.43-23.44 78.69zm64.51 102.41c27.71-13.24 48.02-39.19 53.3-70.41h-39.09c-1.49 27.03-6.53 51.35-14.21 70.41zM416 0H64C28.65 0 0 28.65 0 64v384c0 35.35 28.65 64 64 64h352c17.67 0 32-14.33 32-32V32c0-17.67-14.33-32-32-32zm-80 416H112c-8.8 0-16-7.2-16-16s7.2-16 16-16h224c8.8 0 16 7.2 16 16s-7.2 16-16 16zm-112-96c-70.69 0-128-57.31-128-128S153.31 64 224 64s128 57.31 128 128-57.31 128-128 128zm41.08-214.41c7.68 19.06 12.72 43.38 14.21 70.41h39.09c-5.28-31.22-25.59-57.17-53.3-70.41z"/></svg>
                                        <div>
                                            <p class="mb-0">TIPO DOCUMENTO: <span class="text-red"><span class="text-red">*</span></p>
                                        </div>
                                    </div>
                                    <select class="form-control form-control-sm mt-1 input-comun @error('f.tipoDocumento') is-invalid @enderror" wire:model.live="f.tipoDocumento" @error('f.tipoDocumento') aria-invalid="true" title="{{ $message }}" @enderror >
                                        <option value="">Seleccione</option>
                                        <option value="NACIONAL">NACIONAL</option>
                                        <option value="EXTRANJERO">EXTRANJERO</option>
                                    </select>
                                </label>
                            </div>
                            <div class="col-sm">
                                <label class="d-block small font-weight-bold">
                                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M172.268 501.67C26.97 291.031 0 269.413 0 192 0 85.961 85.961 0 192 0s192 85.961 192 192c0 77.413-26.97 99.031-172.268 309.67-9.535 13.774-29.93 13.773-39.464 0zM192 272c44.183 0 80-35.817 80-80s-35.817-80-80-80-80 35.817-80 80 35.817 80 80 80z"/></svg>
                                        <div>
                                            <p class="mb-0">EXPEDIDO: <span class="text-red"><span class="text-red">*</span></p>
                                        </div>
                                    </div>
                                    <select class="form-control form-control-sm mt-1 input-comun @error('f.idDeptExp') is-invalid @enderror" wire:model.live="f.idDeptExp" @error('f.idDeptExp') aria-invalid="true" title="{{ $message }}" @enderror >
                                        <option value="">Seleccione</option>
                                        @foreach ($catalogos['expedidos'] as $item)
                                        <option value="{{ $item['id'] }}">{{ $item['sigla'] }}</option>
                                        @endforeach
                                    </select>
                                </label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-sm">
                                <label class="d-block small font-weight-bold">
                                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"  width="16" height="16"><path fill="rgb(0, 0, 0)" d="M436 160H12c-6.627 0-12-5.373-12-12v-36c0-26.51 21.49-48 48-48h48V12c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v52h128V12c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v52h48c26.51 0 48 21.49 48 48v36c0 6.627-5.373 12-12 12zM12 192h424c6.627 0 12 5.373 12 12v260c0 26.51-21.49 48-48 48H48c-26.51 0-48-21.49-48-48V204c0-6.627 5.373-12 12-12zm333.296 95.947l-28.169-28.398c-4.667-4.705-12.265-4.736-16.97-.068L194.12 364.665l-45.98-46.352c-4.667-4.705-12.266-4.736-16.971-.068l-28.397 28.17c-4.705 4.667-4.736 12.265-.068 16.97l82.601 83.269c4.667 4.705 12.265 4.736 16.97.068l142.953-141.805c4.705-4.667 4.736-12.265.068-16.97z"/></svg>
                                        <div>
                                            <p class="mb-0">FECHA NACIMIENTO: <span class="text-red"><span class="text-red">*</span></p>
                                        </div>
                                    </div>
                                    <input class="input-comun form-control form-control-sm mt-1 @error('f.fechaNacimiento') is-invalid @enderror" type="date" wire:model.blur="f.fechaNacimiento" @error('f.fechaNacimiento') aria-invalid="true" title="{{ $message }}" @enderror >
                                </label>
                            </div>
                            <div class="col-sm">
                                <label class="d-block small font-weight-bold">
                                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M288 0c-69.59 0-126 56.41-126 126 0 56.26 82.35 158.8 113.9 196.02 6.39 7.54 17.82 7.54 24.2 0C331.65 284.8 414 182.26 414 126 414 56.41 357.59 0 288 0zm0 168c-23.2 0-42-18.8-42-42s18.8-42 42-42 42 18.8 42 42-18.8 42-42 42zM20.12 215.95A32.006 32.006 0 0 0 0 245.66v250.32c0 11.32 11.43 19.06 21.94 14.86L160 448V214.92c-8.84-15.98-16.07-31.54-21.25-46.42L20.12 215.95zM288 359.67c-14.07 0-27.38-6.18-36.51-16.96-19.66-23.2-40.57-49.62-59.49-76.72v182l192 64V266c-18.92 27.09-39.82 53.52-59.49 76.72-9.13 10.77-22.44 16.95-36.51 16.95zm266.06-198.51L416 224v288l139.88-55.95A31.996 31.996 0 0 0 576 426.34V176.02c0-11.32-11.43-19.06-21.94-14.86z"/></svg>
                                        <div>
                                            <p class="mb-0">DEP. NAC: <span class="text-red"><span class="text-red">*</span></p>
                                        </div>
                                    </div>
                                    <select class="form-control form-control-sm mt-1 input-comun @error('f.idDepartamento') is-invalid @enderror" wire:model.live="f.idDepartamento" @error('f.idDepartamento') aria-invalid="true" title="{{ $message }}" @enderror >
                                        <option value="">Seleccione</option>
                                        @foreach ($catalogos['departamentos'] as $item)
                                        <option value="{{ $item['id'] }}">{{ $item['nombre'] }}</option>
                                        @endforeach
                                    </select>
                                </label>
                            </div>
                            <div class="col-sm">
                                <label class="d-block small font-weight-bold">
                                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M288 0c-69.59 0-126 56.41-126 126 0 56.26 82.35 158.8 113.9 196.02 6.39 7.54 17.82 7.54 24.2 0C331.65 284.8 414 182.26 414 126 414 56.41 357.59 0 288 0zm0 168c-23.2 0-42-18.8-42-42s18.8-42 42-42 42 18.8 42 42-18.8 42-42 42zM20.12 215.95A32.006 32.006 0 0 0 0 245.66v250.32c0 11.32 11.43 19.06 21.94 14.86L160 448V214.92c-8.84-15.98-16.07-31.54-21.25-46.42L20.12 215.95zM288 359.67c-14.07 0-27.38-6.18-36.51-16.96-19.66-23.2-40.57-49.62-59.49-76.72v182l192 64V266c-18.92 27.09-39.82 53.52-59.49 76.72-9.13 10.77-22.44 16.95-36.51 16.95zm266.06-198.51L416 224v288l139.88-55.95A31.996 31.996 0 0 0 576 426.34V176.02c0-11.32-11.43-19.06-21.94-14.86z"/></svg>
                                        <div>
                                            <p class="mb-0">PRO. NAC: @unless($this->sinDepartamentoNacimiento())<span class="text-red">*</span>@endunless</p>
                                        </div>
                                    </div>
                                    <select class="form-control form-control-sm mt-1 input-comun @error('f.idProvincia') is-invalid @enderror" wire:model.live="f.idProvincia" @error('f.idProvincia') aria-invalid="true" title="{{ $message }}" @enderror @disabled($this->sinDepartamentoNacimiento() || empty($f['idDepartamento']))>
                                        <option value="">Seleccione</option>
                                        @foreach ($catalogos['provincias'] as $item)
                                        <option value="{{ $item['id'] }}">{{ $item['nombre'] }}</option>
                                        @endforeach
                                    </select>
                                </label>
                            </div>
                            <div class="col-sm">
                                <label class="d-block small font-weight-bold">
                                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M288 0c-69.59 0-126 56.41-126 126 0 56.26 82.35 158.8 113.9 196.02 6.39 7.54 17.82 7.54 24.2 0C331.65 284.8 414 182.26 414 126 414 56.41 357.59 0 288 0zm0 168c-23.2 0-42-18.8-42-42s18.8-42 42-42 42 18.8 42 42-18.8 42-42 42zM20.12 215.95A32.006 32.006 0 0 0 0 245.66v250.32c0 11.32 11.43 19.06 21.94 14.86L160 448V214.92c-8.84-15.98-16.07-31.54-21.25-46.42L20.12 215.95zM288 359.67c-14.07 0-27.38-6.18-36.51-16.96-19.66-23.2-40.57-49.62-59.49-76.72v182l192 64V266c-18.92 27.09-39.82 53.52-59.49 76.72-9.13 10.77-22.44 16.95-36.51 16.95zm266.06-198.51L416 224v288l139.88-55.95A31.996 31.996 0 0 0 576 426.34V176.02c0-11.32-11.43-19.06-21.94-14.86z"/></svg>
                                        <div>
                                            <p class="mb-0">MUC. NAC: @unless($this->sinDepartamentoNacimiento())<span class="text-red">*</span>@endunless</p>
                                        </div>
                                    </div>
                                    <select class="form-control form-control-sm mt-1 input-comun @error('f.idNacimientoMunicipio') is-invalid @enderror" wire:model.live="f.idNacimientoMunicipio" @error('f.idNacimientoMunicipio') aria-invalid="true" title="{{ $message }}" @enderror @disabled($this->sinDepartamentoNacimiento() || empty($f['idProvincia']))>
                                        <option value="">Seleccione</option>
                                        @foreach ($catalogos['municipios'] as $item)
                                        <option value="{{ $item['id'] }}">{{ $item['nombre'] }}</option>
                                        @endforeach
                                    </select>
                                </label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-sm">
                                <label class="d-block small font-weight-bold">
                                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M128 128c35.3 0 64-28.7 64-64S163.3 0 128 0 64 28.7 64 64s28.7 64 64 64zm384 0c35.3 0 64-28.7 64-64S547.3 0 512 0s-64 28.7-64 64 28.7 64 64 64zm127.3 226.5l-45.6-185.8c-3.3-13.5-15.5-23-29.8-24.2-15 9.7-32.8 15.5-52 15.5-19.2 0-37-5.8-52-15.5-14.3 1.2-26.5 10.7-29.8 24.2l-45.6 185.8C381 369.6 393 384 409.2 384H464v104c0 13.3 10.7 24 24 24h48c13.3 0 24-10.7 24-24V384h54.8c16.2 0 28.2-14.4 24.5-29.5zM336 0h-32c-8.8 0-16 7.2-16 16v480c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V16c0-8.8-7.2-16-16-16zM180.1 144.4c-15 9.8-32.9 15.6-52.1 15.6-19.2 0-37.1-5.8-52.1-15.6C51.3 146.5 32 166.9 32 192v136c0 13.3 10.7 24 24 24h8v136c0 13.3 10.7 24 24 24h80c13.3 0 24-10.7 24-24V352h8c13.3 0 24-10.7 24-24V192c0-25.1-19.3-45.5-43.9-47.6z"/></svg>
                                        <div>
                                            <p class="mb-0">SEXO: <span class="text-red"><span class="text-red">*</span></p>
                                        </div>
                                    </div>
                                    <span class="d-block mt-2">
                                        <input class="@error('f.sexo') is-invalid @enderror" type="radio" wire:model.live="f.sexo" @error('f.sexo') aria-invalid="true" title="{{ $message }}" @enderror value="M" aria-label="Masculino"> M &nbsp; 
                                        <input class="@error('f.sexo') is-invalid @enderror" type="radio" wire:model.live="f.sexo" @error('f.sexo') aria-invalid="true" title="{{ $message }}" @enderror value="F" aria-label="Femenino"> F
                                    </span>
                                </label>
                            </div>
                            <div class="col-sm">
                                <label class="d-block small font-weight-bold">
                                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M528 32H48C21.5 32 0 53.5 0 80v16h576V80c0-26.5-21.5-48-48-48zM0 432c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V128H0v304zm352-232c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H360c-4.4 0-8-3.6-8-8v-16zm0 64c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H360c-4.4 0-8-3.6-8-8v-16zm0 64c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H360c-4.4 0-8-3.6-8-8v-16zM176 192c35.3 0 64 28.7 64 64s-28.7 64-64 64-64-28.7-64-64 28.7-64 64-64zM67.1 396.2C75.5 370.5 99.6 352 128 352h8.2c12.3 5.1 25.7 8 39.8 8s27.6-2.9 39.8-8h8.2c28.4 0 52.5 18.5 60.9 44.2 3.2 9.9-5.2 19.8-15.6 19.8H82.7c-10.4 0-18.8-10-15.6-19.8z"/></svg>
                                        <div>
                                            <p class="mb-0">MATRÍCULA: <span class="text-red"><span class="text-red">*</span></p>
                                        </div>
                                    </div>
                                    <input class="input-comun form-control form-control-sm mt-1" readonly value="{{ $this->matricula() }}">
                                </label>
                            </div>
                            <div class="col-sm">
                                <label class="d-block small font-weight-bold">
                                    <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M497.941 225.941L286.059 14.059A48 48 0 0 0 252.118 0H48C21.49 0 0 21.49 0 48v204.118a48 48 0 0 0 14.059 33.941l211.882 211.882c18.744 18.745 49.136 18.746 67.882 0l204.118-204.118c18.745-18.745 18.745-49.137 0-67.882zM112 160c-26.51 0-48-21.49-48-48s21.49-48 48-48 48 21.49 48 48-21.49 48-48 48zm513.941 133.823L421.823 497.941c-18.745 18.745-49.137 18.745-67.882 0l-.36-.36L527.64 323.522c16.999-16.999 26.36-39.6 26.36-63.64s-9.362-46.641-26.36-63.64L331.397 0h48.721a48 48 0 0 1 33.941 14.059l211.882 211.882c18.745 18.745 18.745 49.137 0 67.882z"/></svg>
                                        <div>
                                            <p class="mb-0">ESTADO CIVIL: <span class="text-red"><span class="text-red">*</span></p>
                                        </div>
                                    </div>
                                    <select class="form-control form-control-sm mt-1 input-comun @error('f.idEstadoCivil') is-invalid @enderror" wire:model.live="f.idEstadoCivil" @error('f.idEstadoCivil') aria-invalid="true" title="{{ $message }}" @enderror >
                                        <option value="">Seleccione</option>
                                        @foreach ($catalogos['estadosCiviles'] as $item)
                                        <option value="{{ $item['id'] }}">{{ $item['nombre'] }}</option>
                                        @endforeach
                                    </select>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
        @endif
        </div>
    </div>
    @if ($documento)
        <div class="card">
            <div class="card-body">
                <h6 class="text-center py-2 small font-weight-bold" style="background:#e7f0f7">REGISTRO DE CONTRATO Y ESPECIALIDAD FISIOTERAPIA</h6>
                <fieldset class="field_estilos">
                    <legend>
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd"
                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                        </svg>
                        <em>Complete la información correspondiente del contrato:</em>
                        <span>*</span>
                    </legend>
                    
                    <div class="form-row">
                        <div class="col-sm">
                            <label class="d-block small font-weight-bold">
                                <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M224 136V0H24C10.7 0 0 10.7 0 24v464c0 13.3 10.7 24 24 24h336c13.3 0 24-10.7 24-24V160H248c-13.2 0-24-10.8-24-24zM64 72c0-4.42 3.58-8 8-8h80c4.42 0 8 3.58 8 8v16c0 4.42-3.58 8-8 8H72c-4.42 0-8-3.58-8-8V72zm0 64c0-4.42 3.58-8 8-8h80c4.42 0 8 3.58 8 8v16c0 4.42-3.58 8-8 8H72c-4.42 0-8-3.58-8-8v-16zm192.81 248H304c8.84 0 16 7.16 16 16s-7.16 16-16 16h-47.19c-16.45 0-31.27-9.14-38.64-23.86-2.95-5.92-8.09-6.52-10.17-6.52s-7.22.59-10.02 6.19l-7.67 15.34a15.986 15.986 0 0 1-14.31 8.84c-.38 0-.75-.02-1.14-.05-6.45-.45-12-4.75-14.03-10.89L144 354.59l-10.61 31.88c-5.89 17.66-22.38 29.53-41 29.53H80c-8.84 0-16-7.16-16-16s7.16-16 16-16h12.39c4.83 0 9.11-3.08 10.64-7.66l18.19-54.64c3.3-9.81 12.44-16.41 22.78-16.41s19.48 6.59 22.77 16.41l13.88 41.64c19.77-16.19 54.05-9.7 66 14.16 2.02 4.06 5.96 6.5 10.16 6.5zM377 105L279.1 7c-4.5-4.5-10.6-7-17-7H256v128h128v-6.1c0-6.3-2.5-12.4-7-16.9z"/></svg>
                                    <div>
                                        <p class="mb-0">TIPO DE CONTRATO: <span class="text-red"><span class="text-red">*</span></p>
                                    </div>
                                </div>
                                <span class="d-block mt-2">
                                    <input class="@error('f.tipoContrato') is-invalid @enderror" type="radio" wire:model.live="f.tipoContrato" @error('f.tipoContrato') aria-invalid="true" title="{{ $message }}" @enderror value="EVENTUAL" aria-label="Eventual"> EVENTUAL &nbsp; 
                                    <input class="@error('f.tipoContrato') is-invalid @enderror" type="radio" wire:model.live="f.tipoContrato" @error('f.tipoContrato') aria-invalid="true" title="{{ $message }}" @enderror value="PERMANENTE" aria-label="Permanente"> PERMANENTE
                                </span>
                            </label>
                        </div>
                        <div class="col-sm">
                            <label class="d-block small font-weight-bold">
                                <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M12 192h424c6.6 0 12 5.4 12 12v260c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V204c0-6.6 5.4-12 12-12zm436-44v-36c0-26.5-21.5-48-48-48h-48V12c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v52H160V12c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v52H48C21.5 64 0 85.5 0 112v36c0 6.6 5.4 12 12 12h424c6.6 0 12-5.4 12-12z"/></svg>
                                    <div>
                                        <p class="mb-0">FECHA INICIO: <span class="text-red"><span class="text-red">*</span></p>
                                    </div>
                                </div>
                                <input class="input-comun form-control form-control-sm mt-1 @error('f.fechaContratoInicio') is-invalid @enderror" type="date" wire:model.blur="f.fechaContratoInicio" @error('f.fechaContratoInicio') aria-invalid="true" title="{{ $message }}" @enderror @disabled($f['tipoContrato'] === 'PERMANENTE')>
                            </label>
                        </div>
                        <div class="col-sm">
                            <label class="d-block small font-weight-bold">
                                <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M12 192h424c6.6 0 12 5.4 12 12v260c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V204c0-6.6 5.4-12 12-12zm436-44v-36c0-26.5-21.5-48-48-48h-48V12c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v52H160V12c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v52H48C21.5 64 0 85.5 0 112v36c0 6.6 5.4 12 12 12h424c6.6 0 12-5.4 12-12z"/></svg>
                                    <div>
                                        <p class="mb-0">FECHA FIN: <span class="text-red"><span class="text-red">*</span></p>
                                    </div>
                                </div>
                                <input class="input-comun form-control form-control-sm mt-1 @error('f.fechaContratoFin') is-invalid @enderror" type="date" wire:model.blur="f.fechaContratoFin" @error('f.fechaContratoFin') aria-invalid="true" title="{{ $message }}" @enderror @disabled($f['tipoContrato'] === 'PERMANENTE')>
                            </label>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-4">
                            <label class="d-block small font-weight-bold">
                                <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M336 0H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48V48c0-26.5-21.5-48-48-48zM144 32h96c8.8 0 16 7.2 16 16s-7.2 16-16 16h-96c-8.8 0-16-7.2-16-16s7.2-16 16-16zm48 128c35.3 0 64 28.7 64 64s-28.7 64-64 64-64-28.7-64-64 28.7-64 64-64zm112 236.8c0 10.6-10 19.2-22.4 19.2H102.4C90 416 80 407.4 80 396.8v-19.2c0-31.8 30.1-57.6 67.2-57.6h5c12.3 5.1 25.7 8 39.8 8s27.6-2.9 39.8-8h5c37.1 0 67.2 25.8 67.2 57.6v19.2z"/></svg>
                                    <div>
                                        <p class="mb-0">ESPECIALIDAD: <span class="text-red"><span class="text-red">*</span></p>
                                    </div>
                                </div>
                                <input class="input-comun form-control form-control-sm mt-1" readonly value="FISIOTERAPIA">
                            </label>
                        </div>
                        <div class="col-md-4">
                            <label class="d-block small font-weight-bold">
                                <div class="flex-row" style="display:flex; align-items: center; gap: 5px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16"><path fill="rgb(0, 0, 0)" d="M319.4 320.6L224 416l-95.4-95.4C57.1 323.7 0 382.2 0 454.4v9.6c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-9.6c0-72.2-57.1-130.7-128.6-133.8zM13.6 79.8l6.4 1.5v58.4c-7 4.2-12 11.5-12 20.3 0 8.4 4.6 15.4 11.1 19.7L3.5 242c-1.7 6.9 2.1 14 7.6 14h41.8c5.5 0 9.3-7.1 7.6-14l-15.6-62.3C51.4 175.4 56 168.4 56 160c0-8.8-5-16.1-12-20.3V87.1l66 15.9c-8.6 17.2-14 36.4-14 57 0 70.7 57.3 128 128 128s128-57.3 128-128c0-20.6-5.3-39.8-14-57l96.3-23.2c18.2-4.4 18.2-27.1 0-31.5l-190.4-46c-13-3.1-26.7-3.1-39.7 0L13.6 48.2c-18.1 4.4-18.1 27.2 0 31.6z"/></svg>
                                    <div>
                                        <p class="mb-0">GRADO ACADÉMICO: <span class="text-red"><span class="text-red">*</span></p>
                                    </div>
                                </div>
                                <select class="form-control form-control-sm mt-1 input-comun @error('f.gradoAcademico') is-invalid @enderror" wire:model.live="f.gradoAcademico" @error('f.gradoAcademico') aria-invalid="true" title="{{ $message }}" @enderror >
                                    <option value="">Seleccione</option>
                                    <option value="DOCTOR">DOCTOR</option>
                                    <option value="DOCTORA">DOCTORA</option>
                                    <option value="LICENCIADO">LICENCIADO</option>
                                    <option value="LICENCIADA">LICENCIADA</option>
                                </select>
                            </label>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
        <div class="text-right">
            <a class="btn btn-outline-danger" href="{{ route('fisioterapia.administracion.fisioterapeutas.index') }}">
                VOLVER
            </a>
            <x-save-button wire:click="validar" wire:loading.attr="disabled" :disabled="$guardado">
                GUARDAR
            </x-save-button>
        </div>
    @endif
</div>
@script
<script>
    $wire.$el.addEventListener('input', (event) => {
        const input = event.target;
        if (!(input instanceof HTMLInputElement) || input.type !== 'text' || event.isComposing) return;
        const value = input.value;
        const upper = value.toLocaleUpperCase('es');
        if (value === upper) return;
        const start = value.slice(0, input.selectionStart).toLocaleUpperCase('es').length;
        const end = value.slice(0, input.selectionEnd).toLocaleUpperCase('es').length;
        input.value = upper;
        input.setSelectionRange(start, end);
    }, true);

    $wire.on('confirmar-incorporacion', async () => {
        const respuesta = await Swal.fire({
            icon:'question',
            title:'¿Registrar fisioterapeuta?',
            showCancelButton:true,
            confirmButtonText:'Sí, guardar',
            cancelButtonText:'Cancelar'
        });
        if (respuesta.isConfirmed) await $wire.guardar();
    });
    $wire.on('incorporacion-guardada', async ({pdf, aviso}) => {
        if (pdf) {
            const ventana = window.open(pdf, '_blank');
            if (ventana) ventana.opener = null;
            await Swal.fire({
                icon: 'success', title: 'Fisioterapeuta registrado',
                text: ventana ? 'Se abrió el documento de credenciales.' : 'El documento de credenciales está listo.',
                confirmButtonText: 'Volver al listado',
                footer: '<a target="_blank" rel="noopener">Abrir PDF de credenciales</a>',
                didOpen: () => { Swal.getFooter().querySelector('a').href = pdf; }
            });
        } else {
            await Swal.fire({
                icon: aviso ? 'warning' : 'success', 
                title: 'Fisioterapeuta registrado',
                text: aviso || 'La incorporación se guardó correctamente.'});
        }
        window.location.assign(@js(route('fisioterapia.administracion.fisioterapeutas.index')));
    });
</script>
@endscript
