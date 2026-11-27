<x-ver-modal id="verModalUsuario" labelledby="verModalUsuarioLabel" title=" VISTA DE USUARIO ">

    <div class="row">
        <div class="col-lg-8">
            <p style="background-color:#c4dff3; text-align:center;border-radius:6px"> <b>DATOS USUARIO</b>
            </p>
            <div class="row">
                <div class="col-lg-4" align="center">
                    <span align="center">
                        @if ($this->fotografia == null)
                            <img src="{{ asset('images/default/user_default.jpg') }}" class="img-thumbnail"
                                alt="..." width="170" height="230">
                        @else
                            <img src="data:image/jpg;base64,{{ $fotografia }}" class="img-thumbnail" alt="..."
                                width="170" height="230">
                        @endif


                        <br><b style="color: green;font-size:20px"><em>ACTIVO</em></b>
                    </span>
                </div>
                <div class="col-lg-8">
                    <br>
                    <p><b>Nombre completo:</b> <em>{{ $v_name }}</em><br>
                        <b>C.I.:</b> <em>{{ $v_ci }}
                            {{ $v_complemento }}</em> <br>
                        <b>Edad:</b> <em>{{ $v_edad }} años</em><br>
                        <b>Fecha Nacimiento:</b> <em>{{ $v_fecha_nacimiento }}</em><br>
                        <b>Usuario:</b> <em>{{ $v_email }}</em><br>
                    </p>
                </div>
                <div class="col-lg-12" align="right">
                    <span align="right">
                        <footer class="blockquote-footer">
                            <p class="card-text"><b>Registrado por:</b>
                                <em>{{ $v_usuario_registrador }}</em> <br>
                                <b>Fecha de registro:</b>
                                <em>{{ $v_fecha_registro }}</em>
                            </p>
                        </footer>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">

            <blockquote class="blockquote mb-0">
                <table class="table table-hover">
                    <thead>
                        <th>#</th>
                        <th>Rol</th>
                    </thead>
                    @foreach ($this->v_roles_asignados as $key => $roles)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td> {{ $roles }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-bookmark-check-fill" viewBox="0 0 16 16"
                                    style="color: green;">
                                    <path fill-rule="evenodd"
                                        d="M2 15.5V2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.74.439L8 13.069l-5.26 2.87A.5.5 0 0 1 2 15.5m8.854-9.646a.5.5 0 0 0-.708-.708L7.5 7.793 6.354 6.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z" />
                                </svg>
                            </td>
                        </tr>
                    @endforeach

                </table>
            </blockquote>
        </div>
    </div>







</x-ver-modal>

<script>
    window.addEventListener('modalVerUsuarios', event => {
        $('#verModalUsuario').modal('show');
    });
</script>
