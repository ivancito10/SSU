import { swaggerHeader, swaggerHeaderCss } from './swagger-header';

export function accountPage(admin: boolean, asignar = false): string {
    return String.raw`<!doctype html>
    <html lang="es">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width,initial-scale=1">
            <title>${asignar ? 'Asignar acceso' : admin ? 'Usuarios' : 'Mi perfil'} · SIGHO APIs</title>
            <link rel="stylesheet" type="text/css" href="./docs/swagger-ui.css">
            <link rel="icon" type="image/png" href="./docs/favicon-32x32.png" sizes="32x32">
            <link rel="icon" type="image/png" href="./docs/favicon-16x16.png" sizes="16x16">
            <link rel="stylesheet" href="/api/docs/assets/swagger-account-pages.css">
        </head>
        <body>${swaggerHeader()}
            <main>
                <h1>${asignar ? 'Asignar acceso' : admin ? 'Usuarios con acceso a Swagger' : 'Mi perfil'}</h1>
                <p id="aviso" role="status" hidden></p>
                ${admin ? `
                <section>
                    <form id="buscar">
                        <label for="termino">${asignar ? 'Buscar usuario institucional' : 'Buscar cuenta de Swagger'} por nombre o identificador</label>
                        <input id="termino" maxlength="100" placeholder="Nombre o identificador de users.email">
                        <button class="btn-verde">Buscar</button>
                    </form>
                    <div style="overflow:auto">
                        <table>
                            <thead>
                                <tr>
                                <th>Nombre</th>
                                <th>Identificador</th>
                                ${asignar ? '' : '<th>Rol</th><th>Cambio de contraseña pendiente</th>'}
                                <th>Estado</th>
                                <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="${asignar ? 'resultados' : 'resultadosUsuariosAPIs'}" ></tbody>
                        </table>
                    </div>
                    <button id="anterior" type="button" class="btn-verde">Anterior</button> 
                    <span id="pagina"></span> 
                    <button id="siguiente" type="button" class="btn-verde">Siguiente</button>
                </section>
                ${asignar ? `<section id="asignar-acceso">
                    <form id="alta">
                        <h2>Asignar acceso</h2>
                        <p id="elegido">Seleccione un usuario de la tabla.</p>
                        <label for="rol">Rol</label>
                        <select id="rol" class="btn-verde">
                            <option class="options" value="CONSULTA">CONSULTA</option>
                            <option value="ADMIN">ADMIN</option>
                        </select>
                        <p><small>Se generará una contraseña temporal exclusiva para Swagger. No cambia la contraseña institucional.</small></p>
                        <button id="crear" disabled class="btn-verde">Crear acceso</button>
                        <div id="resultado-alta" role="status" aria-live="polite" hidden>
                        </div>
                    </form>
                </section>` : ''}` : 
                `<section>
                    <p>Usuario: <strong id="username"></strong></p>
                    <p>Rol: <strong id="role"></strong></p>
                </section>
                <section><h2>Cambiar contraseña</h2>
                    <form id="password">
                        <label for="actual">Contraseña actual</label>
                        <input id="actual" type="password" autocomplete="current-password" required>
                        <label for="nueva">Nueva contraseña</label><input id="nueva" type="password" autocomplete="new-password" minlength="12" required>
                        <label for="confirmacion">Confirmar nueva contraseña</label><input id="confirmacion" type="password" autocomplete="new-password" minlength="12" required>
                        <p><small>Mínimo 12 caracteres. Solo cambia el acceso a Swagger.</small></p>
                        <button class="btn-verde">Guardar contraseña</button>
                    </form>
                </section>`}
            </main>
            ${admin && !asignar ? `
                <dialog id="confirmar-accion" aria-labelledby="titulo-accion" aria-describedby="mensaje-accion">
                    <h2 id="titulo-accion"></h2>
                    <p id="mensaje-accion"></p>
                    <form method="dialog">
                        <button class="btn-verde" value="cancelar" autofocus>Cancelar</button>
                        <button class="btn-verde" value="confirmar">Confirmar</button>
                    </form>
                </dialog>` : ''}
            <script src="/api/docs/assets/swagger-account-pages.js" defer></script>
        </body>
    </html>`;
}