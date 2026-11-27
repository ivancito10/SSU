<div>
    <div class="card-borde p-3">
        <div class="row">
            <div class="col-lg-12">
                <div class="card cabecera mb-3">
                    <b><i class="fas fa-book mr-1"></i> DATOS DEL MANUAL DEL SISTEMA</b>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                <label for="moduloManual" class="font-weight-bold">MÓDULO: <span style="color:#cb4335;">*</span></label>
                <select id="moduloManual" class="form-control input-comun @error('modulo') is-invalid border-danger @enderror" wire:model="modulo">
                    <option value="">Seleccione un módulo...</option>
                    @foreach ($modulos as $mod)
                        <option value="{{ $mod }}">{{ $mod }}</option>
                    @endforeach
                </select>
                @error('modulo') <div class="text-danger small mt-1"><b>{{ $message }}</b></div> @enderror
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                <label for="versionManual" class="font-weight-bold">VERSIÓN: <span style="color:#cb4335;">*</span></label>
                <input type="text" id="versionManual" class="form-control input-comun @error('version') is-invalid border-danger @enderror"
                    wire:model="version" placeholder="Ej. 1.0">
                @error('version') <div class="text-danger small mt-1"><b>{{ $message }}</b></div> @enderror
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                <label for="tituloManual" class="font-weight-bold">TÍTULO DEL MANUAL: <span style="color:#cb4335;">*</span></label>
                <input type="text" id="tituloManual" class="form-control input-comun @error('titulo') is-invalid border-danger @enderror"
                    wire:model="titulo" placeholder="Ej. Manual de Usuario - Módulo Farmacia">
                @error('titulo') <div class="text-danger small mt-1"><b>{{ $message }}</b></div> @enderror
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                <label for="descripcionManual" class="font-weight-bold">DESCRIPCIÓN:</label>
                <textarea id="descripcionManual" rows="2" class="form-control input-comun @error('descripcion') is-invalid border-danger @enderror"
                    wire:model="descripcion" placeholder="Breve detalle del contenido del manual (opcional)..."></textarea>
                @error('descripcion') <div class="text-danger small mt-1"><b>{{ $message }}</b></div> @enderror
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 mb-2">
                <label for="archivoPdfManual" class="font-weight-bold">
                    <i class="fas fa-file-pdf text-danger mr-1"></i> ARCHIVO PDF (MÁX. 20 MB): <span style="color:#cb4335;">*</span>
                </label>
                <input type="file" id="archivoPdfManual" accept="application/pdf,.pdf"
                    class="form-control-file rounded p-2 @error('archivo_pdf') border border-danger is-invalid @else border @enderror"
                    wire:model="archivo_pdf">
                @error('archivo_pdf') <div class="text-danger small mt-1"><b>{{ $message }}</b></div> @enderror

                <div wire:loading wire:target="archivo_pdf" class="text-info small mt-1 font-weight-bold">
                    <i class="fas fa-spinner fa-spin mr-1"></i> Cargando archivo PDF...
                </div>

                @if ($archivo_pdf)
                    <div class="alert alert-light border mt-2 py-2 px-3 small mb-0">
                        <i class="fas fa-check-circle text-success mr-1"></i>
                        <b>Archivo listo:</b> {{ $archivo_pdf->getClientOriginalName() }}
                        ({{ number_format($archivo_pdf->getSize() / 1024, 1) }} KB)
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal-footer pt-3 pb-0 px-0" style="border-top: none !important;">
        <button type="button" class="btn btn-outline-danger" wire:click="cancelar_registro_manual" data-dismiss="modal">
            <i class="fas fa-times-circle mr-1"></i> CANCELAR
        </button>
        <button type="button" class="btn btn-outline-success" wire:click="guardar_manual" wire:loading.attr="disabled" wire:target="guardar_manual,archivo_pdf">
            <span wire:loading.remove wire:target="guardar_manual"><i class="fas fa-save mr-1"></i> GUARDAR</span>
            <span wire:loading wire:target="guardar_manual"><i class="fas fa-spinner fa-spin mr-1"></i> SUBIENDO A FTP...</span>
        </button>
    </div>
</div>
