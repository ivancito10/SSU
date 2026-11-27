<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    <div class="row">
        <div class="col-lg-12">
            <div
                class=" row d-flex justify-content-between align-items-center pr-4">
                <h4 class="mb-0" style="color:black;">AFILIACIÓN - <b> LISTA DE
                        USUARIOS ESTUDIANTES</b></h4>
                <div class="d-flex">

                    <div class="mr-2">
                        <x-agregar-button wire:click="redirectToForm"> <b>NUEVO
                                USUARIO</b></x-agregar-button>
                    </div>

                </div>
            </div>
        </div>
    </div>
<br>
@include('livewire.Afiliacion.estudiantes-ssue.modalVerUsuarios')
    <div class="row">
        <div class="col-lg-12">
            <livewire:Afiliacion.EstudiantesSsue.usuarios-estudiantes-table />
        </div>
    </div>

</div>
