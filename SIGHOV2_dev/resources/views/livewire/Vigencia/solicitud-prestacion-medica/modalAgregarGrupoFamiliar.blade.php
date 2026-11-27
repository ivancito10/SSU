<div>
    <x-agregar-button data-toggle="modal" data-target="#modalAgregarBen"> AGREGAR BENEFICIARIO</x-agregar-button>
    <!--MODAL PARA VER EL LISTADO DE APORTANTES-->

    <x-ver-modal id="modalAgregarBen" labelledby="modalAgregarBenLabel" title=" GRUPO FAMILIAR ">
        <div class='row'>
            @if ($datos_persona_grupo_familiar == [])
                <b>NO SE TIENE REGISTRO DE FAMILIARES</b>
            @else
                <table class="table table-bordered" wire:ignore>
                    <thead>
                        <tr align="center" style="background-color:#2471a3 ; color:white;">
                            <th scope="col">#</th>
                            <th scope="col">NOMBRE COMPLETO</th>
                            <th scope="col">MATRICULA</th>
                            <th scope="col">CARNET</th>
                            <th scope="col">PARENTESCO</th>
                            <th scope="col">OPCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $counter = 1; @endphp
                        @foreach ($datos_persona_grupo_familiar as $persona_familiar)
                            <tr style="padding: 15px;">
                                <td align="center" style="padding: 4px;">{{ $counter++ }}</td>
                                <td align="center" style="padding: 4px;">
                                    {{ trim(implode(' ', array_filter([$persona_familiar->p_apellido, $persona_familiar->s_apellido, $persona_familiar->nombres])) )}}
                                    
                                </td>
                                <td align="center" style="padding: 4px;">{{ $persona_familiar->matricula_seguro }}</td>
                                <td align="center" style="padding: 4px;">
                                    {{ $persona_familiar->complemento !== null ? $persona_familiar->ci . '-' . $persona_familiar->complemento : $persona_familiar->ci }}
                                </td>
                                <td align="center" style="padding: 4px;">{{ $persona_familiar->tipoAsegurado }}</td>

                                <td align="center" style="padding: 4px;">
                                    <div class="row">
                                        <!--    Boton Agregar o Quitar -->
                                        <div class="col-sm-12">
                                            <x-agregar-button
                                                onclick="agregarBeneficiario('{{ $persona_familiar->p_apellido }} {{ $persona_familiar->s_apellido }} {{ $persona_familiar->nombres }}', '{{ $persona_familiar->id }}')"></x-agregar-button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            @endif

        </div>
        <script>
            function agregarBeneficiario(persona, idPersona) {
                Swal.fire({
                    title: `¿Está seguro de agregar al Beneficiario ${persona}?`,
                    text: "Se registrarán los datos ingresados.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, agregar',
                    cancelButtonText: 'No, cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('agregarSolicitante', [idPersona]);
                        $('#modalAgregarBen').modal('hide');
                    } else {
                        Swal.fire({
                            title: "Completa todos los datos!",
                            text: "Se canceló el registro",
                            icon: "error"
                        });
                    }
                });
            }
        </script>

    </x-ver-modal>

</div>
