<x-big-component id="modalArchivo" labelledby="modalArchivoLabel" title="REGISTRAR ARCHIVO GRUPO"
    saveAction="guardarArchivoGrupo()">
    <div class="card">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-12">

                <div class="col-12 mb-2">
                    <strong>NOMBRE COMPLETO:</strong>
                    <p class="p-1 p-comun-disabled w-100">{{ $nombre }}</p>
                </div>

                <div class="col-12 mb-2">
                    <strong>C.I.:</strong>
                    <p class="p-1 p-comun-disabled w-100">{{ $carnet }}</p>
                </div>

                <div class="col-12 mb-2">
                    <strong>MATRÍCULA:</strong>
                    <p class="p-1 p-comun-disabled w-100">{{ $matricula }}</p>
                </div>

                <div class="col-12 mb-2">
                    <strong>ARCHIVO GRUPO:</strong>
                    <textarea wire:model="text_archivo_grupo" class="p-1 input-comun w-100" placeholder="Ingrese la información"
                        oninput="this.value = this.value.toUpperCase()"></textarea>
                </div>

            </div>

            <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                <!-- Verificar esto !!! porque sm? -->
                <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                    <p style="background-color:{{ $color }} ;text-align:center;color:white; border-radius:6px">
                        <b>
                            TITULAR</b>
                    </p>
                    <div class="row d-flex flex-column align-items-center">
                        <!-- Paso 4. Mostramos la foto en base 64 -->
                        <img src="{{ !empty($foto) ? 'data:image/jpg;base64,' . $foto : asset('images/default_afiliacion.jpg') }}"
                            class="img-thumbnail" width="200" height="200" alt="FTP.{{ $foto }}">
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center">
                            @if ($estado_vigencia == 'VIGENTE')
                                <span class="h3" style="color: green;"><b>VIGENTE</b></span>
                            @elseif($estado_vigencia == 'CESANTE')
                                <span class="h3 parpadeo" style="color: #ecb41d;"><b>CESANTE</b></span>
                            @elseif($estado_vigencia == 'NO VIGENTE')
                                <span class="h3 parpadeo" style="color: red;"><b>NO
                                        VIGENTE</b></span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-big-component>
<script>
    document.addEventListener('modalArchivoGrupo', event => {
        $('#modalArchivo').modal('show');
    });

    function guardarArchivoGrupo() {
        Swal.fire({
            title: '¿Guardar?',
            text: "Se registrarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, llama a la función saveData()
                Livewire.dispatch('guardarArchivoGrupo');
                $('#modalArchivo').modal('hide');
            }
        });

        document.addEventListener('registroArchivoCorrecto', event => {
            Swal.fire({
                title: "¡Guardado!",
                text: "Se guardo correctamente el archivo grupo del asegurado",
                icon: "success"
            });
            $('#modalArchivo').modal('hide');
        });
        document.addEventListener('errorGuardarArchivo', event => {
            Swal.fire({
                title: "¡Error al registrar!",
                text: "No se guardo el archivo grupo del asegudardo",
                icon: "error"
            });
        });
    }
</script>
