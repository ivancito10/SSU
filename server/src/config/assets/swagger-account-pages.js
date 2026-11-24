let csrf = '',
    page = 1,
    lastPage = 1,
    selected = null,
    search = '';
const resultadoAlta = (message) => {
    const el = document.getElementById('resultado-alta');
    el.textContent = message;
    el.hidden = !message;
};
const aviso = (message) => {
    const el = document.getElementById('aviso');
    el.textContent = message;
    el.hidden = !message;
};
async function api(path, body) {
    const r = await fetch('/api/docs/' + path, {
        method: body ? 'POST' : 'GET',
        credentials: 'same-origin',
        cache: 'no-store',
        headers: body
            ? { 'Content-Type': 'application/json', 'X-CSRF-Token': csrf }
            : {},
        ...(body ? { body: JSON.stringify(body) } : {}),
    });
    if (r.status === 401) {
        location.replace('/api/docs/login');
        throw Error('Sesión vencida');
    }
    const data = await r.json();
    if (!r.ok) throw Error(data.message || 'No se pudo completar la operación');
    return data;
}
async function session() {
    const s = await api('session');
    csrf = s.csrf;
    currentUsername = s.username;
    document.getElementById('asignar').hidden = s.rol !== 'ADMIN' || s.debeCambiarPassword;
    document.getElementById('administrar').hidden = s.rol !== 'ADMIN' || s.debeCambiarPassword;
    return s;
}
let currentUsername;
const assignment = document.getElementById('alta') !== null;
const accounts = document.getElementById('resultadosUsuariosAPIs') !== null;
function confirmarAccion(title, username) {
    const modal = document.getElementById('confirmar-accion');
    document.getElementById('titulo-accion').textContent = title;
    document.getElementById('mensaje-accion').textContent = '¿' + title + ' para ' + username + '?';
    modal.returnValue = '';
    return new Promise(resolve => {
        modal.addEventListener('close', () => resolve(modal.returnValue === 'confirmar'), { once: true });
        modal.showModal();
    });
}

function accountActions(user, cell) {
    const actions = user.estado
        ? [['restablecer', 'Restablecer contraseña'], ['baja', 'Dar de baja']]
        : [['rehabilitar', 'Rehabilitar usuario']];
    for (const [action, title] of actions) {
        const button = document.createElement('button');
        button.type = 'button';
        button.classList.add('btn-verde');
        if(action === 'restablecer') {
            const svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16"><path fill="currentColor" d="M383.9 308.3l23.9-62.6c4-10.5-3.7-21.7-15-21.7h-58.5c11-18.9 17.8-40.6 17.8-64v-.3c39.2-7.8 64-19.1 64-31.7 0-13.3-27.3-25.1-70.1-33-9.2-32.8-27-65.8-40.6-82.8-9.5-11.9-25.9-15.6-39.5-8.8l-27.6 13.8c-9 4.5-19.6 4.5-28.6 0L182.1 3.4c-13.6-6.8-30-3.1-39.5 8.8-13.5 17-31.4 50-40.6 82.8-42.7 7.9-70 19.7-70 33 0 12.6 24.8 23.9 64 31.7v.3c0 23.4 6.8 45.1 17.8 64H56.3c-11.5 0-19.2 11.7-14.7 22.3l25.8 60.2C27.3 329.8 0 372.7 0 422.4v44.8C0 491.9 20.1 512 44.8 512h358.4c24.7 0 44.8-20.1 44.8-44.8v-44.8c0-48.4-25.8-90.4-64.1-114.1zM176 480l-41.6-192 49.6 32 24 40-32 120zm96 0l-32-120 24-40 49.6-32L272 480zm41.7-298.5c-3.9 11.9-7 24.6-16.5 33.4-10.1 9.3-48 22.4-64-25-2.8-8.4-15.4-8.4-18.3 0-17 50.2-56 32.4-64 25-9.5-8.8-12.7-21.5-16.5-33.4-.8-2.5-6.3-5.7-6.3-5.8v-10.8c28.3 3.6 61 5.8 96 5.8s67.7-2.1 96-5.8v10.8c-.1.1-5.6 3.2-6.4 5.8z"/></svg>'
            button.innerHTML = svg;
        }
        if(action === 'baja') {
            const svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512" width="16" height="16"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"/></svg>'
            button.innerHTML = svg;
        }
        if (action === 'rehabilitar') {
            const svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="16" height="16"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"/></svg>'
            button.innerHTML = svg;
        }
        button.title = title;
        button.setAttribute('aria-label', title);
        button.disabled = user.username === currentUsername;
        button.onclick = async () => {
            if (!(await confirmarAccion(title, user.username))) return;
            const buttons = cell.querySelectorAll('button');
            buttons.forEach(item => item.disabled = true);
            try {
                const data = await api('usuariosAPIs/' + encodeURIComponent(user.id) + '/' + action, {});
                await list();
                aviso(data.passwordTemporal
                    ? 'Contraseña temporal de ' + user.username + ': ' + data.passwordTemporal
                    : (data.estado ? 'Se rehabilitó el acceso de ' : 'Se dio de baja el acceso de ') + user.username + '.');
            } catch (error) {
                aviso(error.message);
                buttons.forEach(item => item.disabled = false);
            }
        };
        cell.appendChild(button);
    }
}
async function list() {
    const url = accounts ? 'usuariosAPIs/buscar?buscar=' : 'usuarios/buscar?buscar=';
    const section = accounts ? 'resultadosUsuariosAPIs' : 'resultados';
    try {
        const d = await api(
            url +
                encodeURIComponent(search) +
                '&pagina=' +
                page,
        );

        lastPage = d.lastPage;
        const tbody = document.getElementById(section);

        tbody.replaceChildren();
        for (const u of d.datos) {
            const tr = document.createElement('tr');
            for (const v of [
                u.name,
                u.username,
                ...(accounts ? [u.rol, u.debeCambiarPassword ? 'Sí' : 'No'] : []),
                u.estado ? 'Activo' : 'Inactivo',
            ]) {
                const td = document.createElement('td');
                td.textContent = v || '—';
                tr.appendChild(td);
            }
            const td = document.createElement('td');
            if (accounts) {
                const actions = document.createElement('div');
                actions.className = 'acciones-usuario';
                accountActions(u, actions);
                td.appendChild(actions);
                tr.appendChild(td);
                tbody.appendChild(tr);
                continue;
            }
            const b = document.createElement('button');
            b.type = 'button';
            b.classList.add('btn-verde');
            b.textContent = 'Seleccionar';
            b.disabled = !u.estado || !u.username;
            b.onclick = () => {
                selected = String(u.id);
                document.getElementById('elegido').textContent =
                    u.name + ' · ' + u.username;
                document.getElementById('crear').disabled = false;
                resultadoAlta('');
                document.getElementById('rol').focus({ preventScroll: true });
                document
                    .getElementById('asignar-acceso')
                    .scrollIntoView({
                        behavior: window.matchMedia(
                            '(prefers-reduced-motion: reduce)',
                        ).matches
                            ? 'auto'
                            : 'smooth',
                        block: 'start',
                    });
            };
            td.appendChild(b);
            tr.appendChild(td);
            tbody.appendChild(tr);
        }
        document.getElementById('pagina').textContent =
            page + ' / ' + lastPage + ' · ' + d.total + ' usuarios';
        document.getElementById('anterior').disabled = page <= 1;
        document.getElementById('siguiente').disabled = page >= lastPage;
    } catch (e) {
        aviso(e.message);
    }
}

session()
    .then((s) => {
        if (assignment || accounts) {
            if (s.debeCambiarPassword) {
                location.replace('/api/docs/perfil');
                return;
            }
            list();
        } else {
            document.getElementById('username').textContent = s.username;
            document.getElementById('role').textContent = s.rol;
            if (s.debeCambiarPassword)
                aviso('Debe cambiar su contraseña temporal para continuar.');
        }
    })
    .catch((e) => aviso(e.message));
if (assignment || accounts) {
    document.getElementById('buscar').onsubmit = (e) => {
        e.preventDefault();
        search = document.getElementById('termino').value.trim();
        page = 1;
        aviso('');
        list();
    };
    document.getElementById('anterior').onclick = () => {
        if (page > 1) {
            page--;
            list();
        }
    };
    document.getElementById('siguiente').onclick = () => {
        if (page < lastPage) {
            page++;
            list();
        }
    };
    if (assignment) document.getElementById('alta').onsubmit = async (e) => {
        e.preventDefault();
        const b = document.getElementById('crear');
        b.disabled = true;
        resultadoAlta('');
        try {
            const d = await api('usuarios', {
                idUser: selected,
                rol: document.getElementById('rol').value,
            });
            resultadoAlta(
                'Cuenta creada: ' +
                    d.username +
                    '\nRol: ' +
                    d.rol +
                    '\nContraseña temporal: ' +
                    d.passwordTemporal +
                    '\nCópiela y entréguela por un medio privado. Se muestra solo en esta respuesta.',
            );
            selected = null;
            document.getElementById('elegido').textContent =
                'Seleccione otro usuario.';
        } catch (err) {
            resultadoAlta(err.message);
            b.disabled = !selected;
        }
    };
} else
    document.getElementById('password').onsubmit = async (e) => {
        e.preventDefault();
        const form = e.target,
            button = form.querySelector('button');
        button.disabled = true;
        try {
            const d = await api('perfil/password', {
                actual: document.getElementById('actual').value,
                nueva: document.getElementById('nueva').value,
                confirmacion: document.getElementById('confirmacion').value,
            });
            form.reset();
            await session();
            aviso(d.message);
        } catch (err) {
            aviso(err.message);
        } finally {
            button.disabled = false;
        }
    };
