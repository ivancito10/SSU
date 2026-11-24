// Encabezado compartido por documentación, perfil y administración de cuentas.
export const swaggerHeaderCss = `
    .sighov-header { box-sizing:border-box; width:100%; background:#1b1b1b; color:#fff; font-family:Arial,sans-serif; }
    .sighov-header-inner { box-sizing:border-box; max-width:1460px; min-height:60px; margin:auto; padding:10px 20px; display:flex; align-items:center; gap:20px; flex-wrap:wrap; }
    .sighov-header .sighov-brand { display:flex; align-items:center; gap:8px; margin-right:auto; color:#fff; text-decoration:none; }
    .sighov-brand-icon { display:grid; place-items:center; width:62.5px; height:62.5px; border-radius:100%; background:#85ea2d; color:#173300; font:bold 20px monospace; }
    .sighov-brand-span {padding: 0px; !important}
    .sighov-brand-name { display:block; font-size:22px; font-weight:400; line-height:24px; }
    .sighov-brand-caption { display:block; font-size:10px; color:#ddd; letter-spacing:.5px; }
    .sighov-header nav { display:flex; align-items:center; gap:16px; flex-wrap:wrap; }
    .sighov-header nav a { color:#fff; text-decoration:none; font-size:13px !important; font-weight:700; }
    .sighov-header nav a:hover { text-decoration:underline; }
    .sighov-header nav a[hidden] { display:none!important; }
    .sighov-header nav .sighov-logout { background:#85eb2c; border-radius:4px; border:0.5px solid #85EB2C; padding:9px 18px; transition-property: all; transition-duration: 0.5s; }
    .sighov-header nav .sighov-logout:hover { background:#ffffff; text-decoration:none; color: #85eb2c;}
    @media(max-width:480px) { .sighov-header-inner { gap:12px; } .sighov-header nav { gap:12px; } }
`;

export function swaggerHeader(): string {
    return `
    <header class="sighov-header" id="swagger-shared-header">
        <div class="sighov-header-inner">
            <a class="sighov-brand" href="/api/docs" aria-label="Swagger: documentación de APIs">
                <span class="sighov-brand-icon" aria-hidden="true">{··}</span>
                <span class="sighov-brand-span">
                    <span class="sighov-brand-name">Swagger</span>
                    <span class="sighov-brand-caption">SIGHO · APIs</span>
                </span>
            </a>
            <nav aria-label="Navegación de Swagger">
                <a href="/api/docs/perfil">Mi perfil</a>
                <a id="administrar" href="/api/docs/usuarios" hidden>Usuarios</a>
                <a id="asignar" href="/api/docs/asignar-acceso" hidden>Asignar acceso</a>
                <a class="sighov-logout" href="/api/docs/logout">Cerrar sesión</a>
            </nav>
        </div>
    </header>`;
}