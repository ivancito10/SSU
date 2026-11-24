import { join } from 'node:path';
import type { INestApplication } from '@nestjs/common';
import type {
    Request,
    Response,
    NextFunction,
} from 'express';

import { getDataSourceToken } from '@nestjs/typeorm';
import { DataSource } from 'typeorm';
import { DB_CONNECTIONS } from './database.constants';
import { SwaggerAccounts, SwaggerInputError } from './swagger-accounts';
import { accountPage } from './swagger-account-pages';
import cookieParser from 'cookie-parser';
import express from 'express';

const COOKIE_NAME = 'sighov_swagger_session';

const SESSION_DURATION =
    8 * 60 * 60 * 1000; // 8 horas


function noCache( req: Request, res: Response, next: NextFunction, ) {
    res.setHeader(
        'Cache-Control',
        'no-store, no-cache, must-revalidate, private',
    );

    res.setHeader(
        'Pragma',
        'no-cache',
    );

    res.setHeader(
        'Expires',
        '0',
    );

    next();
}

function loginHtml(
    error = false,
): string {
    return `
<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        SIGHO · API Docs
    </title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;

            font-family:
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Roboto,
                Arial,
                sans-serif;

            background: #f5f5f5;

            min-height: 100vh;

            color: #3b4151;
        }


        /* ============================= */
        /* HEADER                       */
        /* ============================= */

        .header {
            height: 72px;

            background: #1b1b1b;

            display: flex;

            align-items: center;

            padding: 0 40px;

            box-shadow:
                0 2px 8px
                rgba(0, 0, 0, .18);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;

            color: white;

            font-size: 20px;
            font-weight: 700;
        }

        .api-badge {
            background: #49cc90;

            color: #fff;

            font-size: 12px;
            font-weight: 700;

            padding: 6px 10px;

            border-radius: 4px;
        }


        /* ============================= */
        /* CONTENIDO                    */
        /* ============================= */

        .container {
            min-height:
                calc(100vh - 72px);

            display: flex;

            justify-content: center;

            align-items: center;

            padding: 30px;
        }


        .login-card {
            width: 100%;
            max-width: 440px;

            background: #fff;

            border-radius: 7px;

            box-shadow:
                0 3px 15px
                rgba(0, 0, 0, .12);

            overflow: hidden;

            border:
                1px solid #e5e5e5;
        }


        .card-header {
            padding: 30px 32px 15px;
        }


        .title {
            margin: 0 0 8px;

            font-size: 26px;

            color: #3b4151;
        }


        .subtitle {
            margin: 0;

            color: #777;

            line-height: 1.5;

            font-size: 14px;
        }


        .form {
            padding:
                20px 32px 32px;
        }


        .field {
            margin-bottom: 20px;
        }


        label {
            display: block;

            margin-bottom: 8px;

            font-size: 14px;

            font-weight: 600;

            color: #3b4151;
        }


        input {
            width: 100%;

            height: 46px;

            padding: 0 13px;

            border:
                1px solid #d8d8d8;

            border-radius: 4px;

            font-size: 15px;

            outline: none;

            transition:
                border .2s,
                box-shadow .2s;
        }


        input:focus {
            border-color: #49cc90;

            box-shadow:
                0 0 0 3px
                rgba(73, 204, 144, .15);
        }


        .password-container {
            position: relative;
        }


        .password-container input {
            padding-right: 70px;
        }


        .show-password {
            position: absolute;

            right: 12px;
            top: 50%;

            transform:
                translateY(-50%);

            border: none;

            background: none;

            cursor: pointer;

            color: #49cc90;

            font-size: 13px;

            font-weight: 600;
        }


        .login-button {
            width: 100%;

            height: 47px;

            border: 0;

            border-radius: 4px;

            background: #49cc90;

            color: #fff;

            font-size: 15px;

            font-weight: 700;

            cursor: pointer;

            transition:
                background .2s,
                transform .1s;
        }


        .login-button:hover {
            background: #3bb77e;
        }


        .login-button:active {
            transform: scale(.99);
        }


        .error {
            margin-bottom: 20px;

            padding: 12px 14px;

            border-radius: 4px;

            border:
                1px solid #f93e3e;

            background: #fff5f5;

            color: #b62323;

            font-size: 14px;
        }


        .info {
            margin-top: 22px;

            padding-top: 20px;

            border-top:
                1px solid #eee;

            color: #888;

            font-size: 12px;

            line-height: 1.5;

            text-align: center;
        }


        .lock {
            width: 52px;
            height: 52px;

            display: flex;

            align-items: center;
            justify-content: center;

            margin-bottom: 20px;

            border-radius: 50%;

            background:
                rgba(73, 204, 144, .13);

            color: #49cc90;

            font-size: 23px;
        }


        @media (
            max-width: 600px
        ) {

            .header {
                padding: 0 20px;
            }

            .container {
                padding: 18px;
            }

            .card-header,
            .form {
                padding-left: 23px;
                padding-right: 23px;
            }

        }

    </style>

</head>


<body>

    <header class="header">

        <div class="brand">

            <span>
                SIGHO
            </span>

            <span class="api-badge">
                API
            </span>

        </div>

    </header>


    <main class="container">

        <div class="login-card">

            <div class="card-header">

                <div class="lock">
                    🔒
                </div>

                <h1 class="title">
                    Documentación API
                </h1>

                <p class="subtitle">
                    Área de acceso restringido
                    para personal autorizado.
                </p>

            </div>


            <form
                class="form"
                method="POST"
                action="/api/docs/login"
            >

                ${
                    error
                        ? `
                        <div class="error">
                            Usuario o contraseña incorrectos.
                        </div>
                        `
                        : ''
                }


                <div class="field">

                    <label for="username">
                        Usuario
                    </label>

                    <input
                        id="username"
                        name="username"
                        type="text"
                        autocomplete="username"
                        placeholder="Ingrese su usuario"
                        required
                        autofocus
                    >

                </div>


                <div class="field">

                    <label for="password">
                        Contraseña
                    </label>

                    <div
                        class="password-container"
                    >

                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            placeholder="Ingrese su contraseña"
                            required
                        >

                        <button
                            type="button"
                            class="show-password"
                            onclick="mostrarPassword()"
                        >
                            Ver
                        </button>

                    </div>

                </div>


                <button
                    class="login-button"
                    type="submit"
                >
                    Ingresar a Swagger
                </button>


                <div class="info">

                    SIGHO V2 ·
                    Seguro Social Universitario La Paz

                    <br>

                    Acceso exclusivo para
                    personal autorizado.

                </div>

            </form>

        </div>

    </main>


    <script>

        function mostrarPassword() {

            const input =
                document.getElementById(
                    'password'
                );

            const button =
                document.querySelector(
                    '.show-password'
                );

            if (
                input.type === 'password'
            ) {

                input.type = 'text';

                button.textContent =
                    'Ocultar';

            } else {

                input.type = 'password';

                button.textContent =
                    'Ver';

            }

        }

    </script>

</body>

</html>
`;
}
export function configurarSwaggerAuth(app: INestApplication) {
    const accounts = new SwaggerAccounts(
        app.get<DataSource>(getDataSourceToken(DB_CONNECTIONS.SIGHOV)),
        process.env.SWAGGER_SESSION_SECRET ?? '',
    );
    const server = app.getHttpAdapter().getInstance();
    app.use(cookieParser());
    app.use(express.json({ limit: '8kb' }));
    app.use(express.urlencoded({ extended: false, limit: '8kb' }));
    const attempts = new Map<string, { count: number; until: number }>();
    const unavailable = (res: Response) => res.status(503).send('No se pudo consultar el acceso a la documentación. Intente nuevamente.');
    server.get('/api/docs/login', noCache, async (req: Request, res: Response) => {
        try {
            if (await accounts.authenticated(req.cookies?.[COOKIE_NAME])) return res.redirect('/api/docs');
            return res.status(200).send(loginHtml(req.query.error === '1'));
        } catch { return unavailable(res); }
    });
    server.post('/api/docs/login', noCache, async (req: Request, res: Response) => {
        const now = Date.now();
        for (const [key, value] of attempts) 
        if (value.until <= now) attempts.delete(key);
        const key = req.ip ?? req.socket.remoteAddress ?? 'unknown';
        const attempt = attempts.get(key) ?? { count: 0, until: now + 10 * 60 * 1000 };
        if (attempt.count >= 20 || (!attempts.has(key) && attempts.size >= 10000)) {
            res.setHeader('Retry-After', '600');
            return res.status(429).send('Demasiados intentos. Intente nuevamente en diez minutos.');
        }
        attempt.count++;
        attempts.set(key, attempt);
        try {
            const token = await accounts.login(req.body?.username, req.body?.password);
            if (!token) {
                res.clearCookie(COOKIE_NAME, { path: '/' });
                return res.redirect('/api/docs/login?error=1');
            }
            res.cookie(COOKIE_NAME, token, {
                httpOnly: true, 
                secure: process.env.NODE_ENV === 'production',
                sameSite: 'strict', 
                maxAge: SESSION_DURATION, 
                path: '/',
            });
            const account = await accounts.current(token);
            return res.redirect(account?.debeCambiarPassword ? '/api/docs/perfil' : '/api/docs');
        } catch { return unavailable(res); }
    });
    server.get('/api/docs/session', noCache, async (req: Request, res: Response) => {
        try {
            const account = await accounts.current(req.cookies?.[COOKIE_NAME]);
            if (!account) return res.status(401).json({ authenticated: false });
            return res.json({ authenticated: true, username: account.username, rol: account.rol,
                debeCambiarPassword: account.debeCambiarPassword, csrf: accounts.csrf(req.cookies[COOKIE_NAME]) });
        } catch { return res.status(503).json({ authenticated: false }); }
    });
    server.get('/api/docs/logout', noCache, (_req: Request, res: Response) => {
        res.clearCookie(COOKIE_NAME, { path: '/' });
        return res.redirect('/api/docs/login');
    });
    const accountRoute = (admin: boolean, mutate: boolean, handler: (req: Request, res: Response, account: NonNullable<Awaited<ReturnType<SwaggerAccounts['current']>>>) => Promise<unknown>) =>
        async (req: Request, res: Response) => {
            try {
                const token = req.cookies?.[COOKIE_NAME];
                const account = await accounts.current(token);
                if (!account) return res.status(401).json({ message: 'Inicie sesión' });
                if (admin && (account.rol !== 'ADMIN' || account.debeCambiarPassword)) return res.status(403).json({ message: 'Acceso denegado' });
                if (mutate) {
                    if (req.get('X-CSRF-Token') !== accounts.csrf(token)) return res.status(403).json({ message: 'Solicitud inválida; recargue la página' });
                    const key = 'account:' + account.id;
                    const attempt = attempts.get(key) ?? { count: 0, until: Date.now() + 600000 };
                    if (attempt.until <= Date.now()) { attempt.count = 0; attempt.until = Date.now() + 600000; }
                    if (++attempt.count > 20) return res.status(429).json({ message: 'Demasiados intentos; espere diez minutos' });
                    attempts.set(key, attempt);
                }
                return await handler(req, res, account);
            } catch (error) {
                if (error instanceof SwaggerInputError) return res.status(error.status).json({ message: error.message });
                return res.status(503).json({ message: 'No se pudo completar la operación. Revise la conexión y la migración de roles.' });
            }
        };
    server.get('/api/docs/assets/swagger-account-pages.js', noCache, accountRoute(false, false, async (_req, res) =>
        res.sendFile(join(__dirname, 'assets', 'swagger-account-pages.js'))));
    server.get('/api/docs/assets/swagger-account-pages.css', noCache, accountRoute(false, false, async (_req, res) =>
        res.sendFile(join(__dirname, 'assets', 'swagger-account-pages.css'))));
    server.get('/api/docs/perfil', noCache, accountRoute(false, false, async (_req, res) => res.type('html').send(accountPage(false))));
    server.get('/api/docs/usuarios', noCache, accountRoute(true, false, async (_req, res) => res.type('html').send(accountPage(true))));
    server.get('/api/docs/usuarios/buscar', noCache, accountRoute(true, false, async (req, res, account) =>
        res.json(await accounts.searchUsers(account, String(req.query.buscar ?? '').trim(), Number(req.query.pagina ?? 1)))));
    server.post('/api/docs/usuarios', noCache, accountRoute(true, true, async (req, res, account) =>
        res.status(201).json(await accounts.create(account, req.body?.idUser, req.body?.rol))));

    server.get('/api/docs/asignar-acceso', noCache, accountRoute(true, false, async (_req, res) => res.type('html').send(accountPage(true, true))));
    for (const action of ['baja', 'restablecer', 'rehabilitar'] as const) {
        server.post('/api/docs/usuariosAPIs/:id/' + action, noCache, accountRoute(true, true, async (req, res, account) =>
            res.json(await accounts.manageAccess(account, req.params.id, action))));
    }
    server.get('/api/docs/usuariosAPIs', noCache, accountRoute(true, false, async (_req, res) => res.type('html').send(accountPage(true))));
    server.get('/api/docs/usuariosAPIs/buscar', noCache, accountRoute(true, false, async (req, res, account) =>
        res.json(await accounts.searchUsuariosAPIs(account, String(req.query.buscar ?? '').trim(), Number(req.query.pagina ?? 1)))));

    server.post('/api/docs/perfil/password', noCache, accountRoute(false, true, async (req, res, account) => {
        const token = await accounts.changePassword(account, req.body?.actual, req.body?.nueva, req.body?.confirmacion);
        res.cookie(COOKIE_NAME, token, { httpOnly: true, secure: process.env.NODE_ENV === 'production', sameSite: 'strict', maxAge: SESSION_DURATION, path: '/' });
        return res.json({ message: 'Contraseña actualizada. Las sesiones anteriores dejaron de ser válidas.' });
    }));
    const protect = async (req: Request, res: Response, next: NextFunction) => {
        try {
            const account = await accounts.current(req.cookies?.[COOKIE_NAME]);
            if (account && !account.debeCambiarPassword) return next();
            if (account?.debeCambiarPassword) return res.redirect('/api/docs/perfil');
            if (req.originalUrl.startsWith('/api/docs-json') || req.originalUrl.startsWith('/api/docs-yaml')) {
                return res.status(401).json({ message: 'Acceso no autorizado a la documentación API' });
            }
            return res.redirect('/api/docs/login');
        } catch { return unavailable(res); }
    };
    app.use(['/api/docs', '/api/docs-json', '/api/docs-yaml'], noCache, protect);
}