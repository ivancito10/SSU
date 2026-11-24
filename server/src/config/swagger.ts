import { swaggerHeader, swaggerHeaderCss } from './swagger-header';
import type { INestApplication } from '@nestjs/common';
import { DocumentBuilder, SwaggerModule } from '@nestjs/swagger';
import type {
    OpenAPIObject,
    SchemaObject,
    ParameterObject,
} from '@nestjs/swagger';
import {
    configurarSwaggerAuth,
} from './swagger-auth';
import { AuthModule } from '../modules/auth/auth.module';
import { PacientesModule } from '../modules/pacientes/pacientes.module';
import { PersonasModule } from '../modules/personas/personas.module';
import { UsuariosModule } from '../modules/usuarios/usuarios.module';
import { EspecialistasModule } from '../modules/especialistas/especialistas.module';
import { SedesModule } from '../modules/sedes/sedes.module';
import { FisioterapeutasModule } from '../modules/fisioterapeutas/fisioterapeutas.module';
import { UsuariosAPIsModule } from '../modules/usuariosAPIs/usuariosAPIs.module';

const texto: SchemaObject = { type: 'string' };
const entero: SchemaObject = {
    type: 'integer',
    minimum: 1,
    maximum: 2147483647,
};
const nullable = (schema: SchemaObject): SchemaObject => ({
    ...schema,
    nullable: true,
});
const objeto = (properties: Record<string, SchemaObject>): SchemaObject => ({
    type: 'object',
    properties,
});
const catalogo = objeto({ 
    id: nullable(entero), 
    nombre: nullable(texto) 
});
const persona = objeto({
    id: entero,
    idPersona: entero,
    ci: texto,
    complemento: nullable(texto),
    nombres: texto,
    primerApellido: nullable(texto),
    segundoApellido: nullable(texto),
    nombreCompleto: texto,
    matricula: nullable(texto),
    sexo: nullable(texto),
    fechaNacimiento: nullable({ type: 'string', format: 'date' }),
    esExtranjero: nullable({ type: 'boolean' }),
    tipoDocumento: nullable({
        type: 'string',
        enum: ['NACIONAL', 'EXTRANJERO'],
    }),
    expedido: objeto({
        id: nullable(entero),
        nombre: nullable(texto),
        sigla: nullable(texto),
    }),
    estadoCivil: catalogo,
    municipioNacimiento: catalogo,
    departamentoNacimiento: catalogo,
    provinciaNacimiento: catalogo,
    tipoAsegurado: catalogo,
    usuarioCreador: catalogo,
    usuarioActualizador: catalogo,
    idEstadoCivil: nullable(entero),
    idDeptExp: nullable(entero),
    idNacimientoMunicipio: nullable(entero),
    idTipoAsegurado: entero,
    afiliado: { type: 'boolean' },
    estadoAsuss: nullable({ type: 'boolean' }),
    p_apellido: nullable(texto),
    s_apellido: nullable(texto),
    matricula_seguro: nullable(texto),
    fecha_nacimiento: nullable({ type: 'string', format: 'date' }),
});
const personaRespuesta = objeto({
    ...Object.fromEntries(Object.entries(persona.properties!).filter(([key]) =>
        !['primerApellido', 'segundoApellido', 'matricula'].includes(key))),
    clave_unica: texto,
    grupo_sanguineo: nullable(texto),
    alergias: nullable(texto),
});
const especialista = objeto(
    Object.fromEntries([
        ...Object.entries(persona.properties!).filter(([k]) =>
            [
                'idPersona',
                'ci',
                'complemento',
                'nombres',
                'primerApellido',
                'segundoApellido',
                'nombreCompleto',
                'matricula',
                'sexo',
                'fechaNacimiento',
                'esExtranjero',
                'tipoDocumento',
                'expedido',
            ].includes(k),
        ),
        ['idEspecialista', entero],
        ['especialidad', catalogo],
        ['estado', nullable({ type: 'boolean' })],
        [
            'tipoContrato',
            nullable({ type: 'string', enum: ['PERMANENTE', 'EVENTUAL'] }),
        ],
        ['contratoDesde', nullable({ type: 'string', format: 'date' })],
        ['contratoHasta', nullable({ type: 'string', format: 'date' })],
        ['foto', nullable(texto)],
        ['gradoAcademico', nullable(texto)],
    ]) as Record<string, SchemaObject>,
);
const sede = objeto({
    id: entero,
    ubicacion: nullable(texto),
    piso: nullable(texto),
    id_residencia: nullable(entero),
    residencia: nullable(texto),
});
const usuario = objeto({
    id: { type: 'string', pattern: '^[1-9][0-9]*$', description: 'ID bigint de administracion.users, como cadena para evitar pérdida de precisión' },
    name: texto,
    email: texto,
    estado: nullable({ type: 'boolean' }),
    imagen: nullable(texto),
    id_persona: nullable(entero),
    created_at: nullable({ type: 'string', format: 'date-time' }),
    updated_at: nullable({ type: 'string', format: 'date-time' }),
});
const usuarioAPI = objeto({
    id: { type: 'string', pattern: '^[1-9][0-9]*$', description: 'ID bigint de administracion.usuarios_api, como cadena para evitar pérdida de precisión' },
    username: texto,
    estado: nullable({ type: 'boolean' }),
    rol: nullable(texto),
    debe_cambiar_password: nullable({ type: 'boolean' }),
    id_user: nullable(entero),
    created_at: nullable({ type: 'string', format: 'date-time' }),
    updated_at: nullable({ type: 'string', format: 'date-time' }),
});
const paciente = objeto({
    idPersona: entero,
    ci: texto,
    matricula: texto,
    nombreCompleto: texto,
    fechaNacimiento: nullable({ type: 'string', format: 'date' }),
    sexo: texto,
    tipoAsegurado: texto,
    estado: { type: 'boolean' },
    institucion: texto,
    instituciones: { type: 'array', items: objeto({
        idInstitucion: entero,
        nombre: texto,
        tipoInstitucion: texto,
        activo: { type: 'boolean' },
    }) },
});
const pacienteAdministracion = objeto({
    nombres: texto,
    p_apellido: texto,
    s_apellido: texto,
    matricula_seguro: texto,
    sexo: texto,
    fecha_nacimiento: texto,
    ci: texto,
    complemento: nullable(texto),
    nacionalidad: texto,
    telefono: { type: 'number' },
    residencia: texto,
});
const query = (
    name: string,
    schema: SchemaObject,
    description?: string,
): ParameterObject => ({
    name,
    in: 'query',
    required: false,
    schema,
    description,
});
const pagina = (item: SchemaObject, total = true) =>
    objeto({
        datos: { type: 'array', items: item },
        ...(total ? { total: { type: 'integer' } as SchemaObject } : {}),
        meta: objeto({
            total: { type: 'integer', minimum: 0 },
            page: { type: 'integer', minimum: 1 },
            lastPage: { type: 'integer', minimum: 1 },
            limit: { type: 'integer', minimum: 1 },
            hasNextPage: { type: 'boolean' },
            hasPrevPage: { type: 'boolean' },
        }),
    });
const json = (schema: SchemaObject) => ({ 'application/json': { schema } });
const error = (statusCode: number, description: string) => ({
    description,
    content: json(
        objeto({
            statusCode: { type: 'integer', enum: [statusCode], example: statusCode },
            message: { oneOf: [texto, { type: 'array', items: texto }], example: description },
            error: { type: 'string', description: 'Etiqueta HTTP incluida por las excepciones estándar de NestJS; puede omitirse en los filtros de dominio.' },
        }),
    ),
});

// Lista explícita
export function limitarDocumento(document: OpenAPIObject): OpenAPIObject {
    const rutas: Record<
        string,
        {
            tag: string;
            summary: string;
            schema: SchemaObject;
            detail?: boolean;
            list?: boolean;
        }
    > = {
        '/api/pacientes': {
            tag: 'Pacientes',
            summary:
                'Buscar pacientes por CI, matrícula o nombre (paginado)',
            schema: objeto({
                data: { type: 'array', items: paciente },
                meta: objeto({
                    total: { type: 'integer', minimum: 0, example: 45 },
                    page: { type: 'integer', minimum: 1, example: 1 },
                    lastPage: { type: 'integer', minimum: 1, example: 3 },
                    limit: { type: 'integer', minimum: 1, example: 20 },
                    hasNextPage: { type: 'boolean', example: true },
                    hasPrevPage: { type: 'boolean', example: false },
                }),
            }),
        },
        '/api/pacientes/{id}': {
            tag: 'Pacientes',
            summary: 'Consultar paciente por ID de persona',
            schema: paciente,
            detail: true,
        },
        '/api/s1/administracion/pacientes': {
            tag: 'Pacientes',
            summary: 'Listado de pacientes para administración / Laravel',
            schema: objeto({
                status: { type: 'integer', example: 200 },
                success: { type: 'boolean' },
                message: texto,
                data: { type: 'array', items: pacienteAdministracion },
            }),
        },
        '/api/personas': {
            tag: 'Personas',
            summary: 'Listar todas las personas (paginado)',
            schema: pagina(personaRespuesta),
            list: true,
        },
        '/api/personas/{id}': {
            tag: 'Personas',
            summary: 'Consultar persona por ID',
            schema: personaRespuesta,
            detail: true,
        },
        '/api/usuarios': {
            tag: 'Usuarios',
            summary: 'Consultar usuarios de etapa 1 con filtros y paginación',
            schema: pagina(usuario),
            list: true,
        },
        '/api/usuarios/{id}': {
            tag: 'Usuarios',
            summary: 'Consultar usuario por ID de administracion.users',
            schema: usuario,
            detail: true,
        },
        '/api/usuariosAPIs': {
            tag: 'UsuariosAPIs',
            summary: 'Consultar usuariosAPIs de etapa 1 con filtros y paginación',
            schema: pagina(usuarioAPI),
            list: true,
        },
        '/api/usuariosAPIs/{id}': {
            tag: 'UsuariosAPIs',
            summary: 'Consultar usuarioAPIs por ID de administracion.usuario_api',
            schema: usuarioAPI,
            detail: true,
        },
        '/api/especialistas': {
            tag: 'Especialistas',
            summary: 'Listar especialistas activos y filtrar por especialidad',
            schema: pagina(especialista, false),
            list: true,
        },
        '/api/especialistas/{id}': {
            tag: 'Especialistas',
            summary: 'Consultar especialista por ID',
            schema: objeto({ especialista }),
            detail: true,
        },
        '/api/especialistas/persona/{idPersona}': {
            tag: 'Especialistas',
            summary: 'Consultar especialista por ID de persona de etapa 1',
            schema: objeto({ especialista }),
            detail: true,
        },
        '/api/auth/login': {
            tag: 'Auth',
            summary: 'Iniciar sesión y obtener accessToken',
            schema: objeto({
                accessToken: texto,
                tokenType: { type: 'string', example: 'Bearer' },
                usuario: objeto({
                    id: texto,
                    username: texto,
                    id_persona: nullable(entero),
                    nombreCompleto: nullable(texto),
                    idEspecialista: nullable(entero),
                }),
            }),
        },
        '/api/fisioterapeutas/catalogos': {
            tag: 'Fisioterapia · Incorporaciones',
            summary: 'Catálogos de registro y nacimiento en cascada',
            schema: objeto({
                estadosCiviles: { type: 'array', items: catalogo },
                expedidos: { type: 'array', items: objeto({ id: entero, nombre: texto, sigla: texto }) },
                departamentos: { type: 'array', items: catalogo },
                provincias: { type: 'array', items: objeto({ id: entero, nombre: texto, idDepartamento: entero }) },
                municipios: { type: 'array', items: objeto({ id: entero, nombre: texto, idProvincia: entero }) },
            }),
        },
        '/api/fisioterapeutas/incorporaciones': {
            tag: 'Fisioterapia · Incorporaciones',
            summary: 'Crear o incorporar una persona a fisioterapia',
            schema: objeto({
                idPersona: entero,
                idEspecialista: entero,
                idUsuario: texto,
                personaCreada: { type: 'boolean' },
                especialistaCreado: { type: 'boolean' },
                usuarioCreado: { type: 'boolean' },
                email: nullable(texto),
                matricula: texto,
                passwordTemporal: {
                    type: 'string',
                    description:
                        'Solo se devuelve cuando se crea un usuario nuevo.',
                },
            }),
        },
        '/api/sedes': {
            tag: 'Sedes',
            summary: 'Listar sedes con residencia',
            schema: pagina(sede),
            list: true,
        },
        '/api/sedes/{id}': {
            tag: 'Sedes',
            summary: 'Consultar sede por ID',
            schema: sede,
            detail: true,
        }
    };
    document.paths = Object.fromEntries(
        Object.entries(document.paths).filter(([path]) => path in rutas),
    );
    document.components = {
        securitySchemes: document.components?.securitySchemes,
        schemas: {},
    };
    for (const [path, item] of Object.entries(document.paths)) {
        const meta = rutas[path];
        const method =
            path === '/api/auth/login' || path.endsWith('/incorporaciones')
                ? 'post'
                : 'get';
        const op = item[method]!;
        document.paths[path] = { [method]: op };
        op.tags = [meta.tag];
        op.summary = meta.summary;
        op.security = path === '/api/auth/login' ? [] : [{ bearer: [] }];
        op.parameters = [];
        op.responses = {
            [method === 'post' && path !== '/api/auth/login' ? '201' : '200']: {
                description: 'Operación exitosa',
                content: json(meta.schema),
            },
            '400': error(400, 'Datos o parámetros inválidos'),
            '401': error(401, path === '/api/auth/login'
                ? 'Credenciales inválidas o usuario inactivo'
                : 'Token Bearer ausente, inválido o vencido'),
            '500': error(500, 'Error interno del servidor'),
        };
        if (meta.detail) {
            op.parameters.push({
                name: path === '/api/especialistas/persona/{idPersona}' ? 'idPersona' : 'id',
                in: 'path',
                required: true,
                schema: path === '/api/usuarios/{id}' ? usuario.properties!.id : entero,
            });
            const recurso = path === '/api/pacientes/{id}' ? 'Paciente'
                : path === '/api/usuarios/{id}' ? 'Usuario'
                : path === '/api/personas/{id}' ? 'Persona'
                : path.startsWith('/api/especialistas/') ? 'Especialista' : 'Sede';
            op.responses['404'] = error(404, `${recurso} no encontrado para el ID solicitado`);
        }
        if (meta.list) {
            op.parameters.push(
                query('pagina', {
                    type: 'integer',
                    minimum: 1,
                    maximum: 1000000,
                    default: 1,
                }),
                query('limite', {
                    type: 'integer',
                    minimum: 1,
                    maximum: 2000,
                    default: 20,
                }),
                query(
                    'buscar',
                    { type: 'string', maxLength: 100 },
                    'Búsqueda parcial',
                ),
            );
            if (path === '/api/especialistas')
                op.parameters.push(
                    query('q', texto, 'Alias de buscar'),
                    query(
                        'especialidad',
                        texto,
                        'Nombre o sigla de especialidad',
                    ),
                    query(
                        'idEspecialidad',
                        entero,
                        'ID exacto de especialidad',
                    ),
                );
        }
        if (path === '/api/especialistas/persona/{idPersona}') {
            op.description = 'Devuelve el mismo objeto especialista que el detalle por ID de especialista. Solo incluye especialistas con estado true. Sin especialista activo devuelve 404. El ID corresponde a administracion.persona de etapa 1.';
            op.responses['409'] = error(409, 'La persona tiene varios especialistas activos; consulte por ID de especialista');
        }
        if (path === '/api/personas') {
            op.description = 'Filtros combinables (AND). ci busca desde el inicio: 123456 coincide con 123456 y 123456ABC, no con 9912345600. ci + complemento compara ambos campos completos sin distinguir mayúsculas. buscar conserva la búsqueda general; si empieza con un número se interpreta como prefijo de CI. Para matrícula numérica use matricula.';
            op.parameters.push(
                query('ci', { type: 'string', maxLength: 100, example: '123456' }, 'Prefijo de CI; con complemento, CI exacto'),
                query('complemento', { type: 'string', maxLength: 100, example: '1A' }, 'Complemento exacto; requiere ci. Ejemplo: ?ci=123456&complemento=1A'),
                query('matricula', { type: 'string', maxLength: 100 }, 'Búsqueda parcial por matrícula'),
                query('nombre', { type: 'string', maxLength: 100 }, 'Busca todas las palabras entre nombres y apellidos, en cualquier orden'),
            );
        }
        if (path === '/api/usuarios') {
            op.description = 'buscar compara name o email, incluso cuando el texto es numérico; nombre busca cada palabra entre name y los nombres/apellidos de persona. No admite filtros por CI, complemento ni matrícula. Sin resultados devuelve datos: [] con HTTP 200.';
            op.parameters.push(
                query('nombre', { type: 'string', maxLength: 100 }, 'Todas las palabras entre name y nombres/apellidos de persona'),
                query('email', { type: 'string', maxLength: 100 }, 'Coincidencia parcial de correo'),
                query('estado', { type: 'boolean' }, 'true: activos; false: inactivos; omitido: todos'),
                query('id_persona', entero, 'ID exacto de administracion.persona'),
            );
        }
        if (path === '/api/usuariosAPIs') {
            op.description = 'buscar compara username';
            op.parameters.push(
                query('username', { type: 'string', maxLength: 100 }, 'Coincidencia parcial de username'),
                query('estado', { type: 'boolean' }, 'true: activos; false: inactivos; omitido: todos'),
                query('id_user', entero, 'ID exacto de administracion.users'),
            );
        }
        if (path === '/api/pacientes')
            op.parameters.push(
                query('page', { type: 'integer', minimum: 1, default: 1 }, 'Número de página, empezando en 1'),
                query('limit', { type: 'integer', minimum: 1, default: 20 }, 'Cantidad de pacientes por página'),
                query('ci', texto, 'Filtro por CI y complemento'),
                query('matricula', texto, 'Filtro por matrícula de seguro'),
                query('nombre', texto, 'Filtro por nombres y apellidos'),
                query(
                    'q',
                    texto,
                    'Búsqueda parcial por CI, matrícula o nombre',
                ),
            );
        if (path === '/api/s1/administracion/pacientes')
            op.parameters.push(
                query(
                    'limite',
                    { type: 'integer', default: 50 },
                    'Cantidad de resultados',
                ),
            );
        if (path === '/api/pacientes')
            op.description = 'Ejemplo: /api/pacientes?page=2&limit=20. Devuelve data y meta con el total y los indicadores de navegación. Sin coincidencias devuelve 200 con data vacío; el 404 corresponde al detalle por ID. Este endpoint utiliza page/limit, no pagina/limite. Si se envían varios filtros, se aplica el primero no vacío en este orden: ci, matricula, nombre, q.';
        if (path === '/api/auth/login')
            op.requestBody = {
                required: true,
                content: json({
                    ...objeto({
                        username: {
                            type: 'string',
                            example: 'usuario@ssulapaz.org',
                        },
                        password: {
                            type: 'string',
                            format: 'password',
                            writeOnly: true,
                        },
                    }),
                    required: ['username', 'password'],
                }),
            };
        if (path === '/api/fisioterapeutas/catalogos') {
            op.description = 'Sin filtros devuelve departamentos, expedidos y estados civiles; provincias y municipios vacíos. Enviar idDepartamento para provincias e idProvincia para municipios. Si envía ambos, se valida su relación.';
            op.parameters.push(query('idDepartamento', entero), query('idProvincia', entero));
        }
        if (path.endsWith('/incorporaciones')) {
            op.description =
                'Envía el formulario completo. idPersona es opcional: al omitirlo busca por CI y complemento, reutiliza la persona si existe o crea persona, especialista y usuario. Un idPersona explícito inexistente devuelve 404. Si hay varios especialistas activos o usuarios, indica sus IDs. Al cambiar de especialidad se crea un nuevo especialista de fisioterapia y se desactivan los anteriores sin modificar su especialidad; un fisioterapeuta activo se reutiliza. El usuario existente se reactiva. idNacimientoMunicipio es obligatorio y determina provincia y departamento; idDeptExp es independiente. Contrato EVENTUAL exige fechas. Conserva credenciales existentes. Ejecuta escrituras reales al pulsar Execute.';
            op.responses['404'] = error(404, 'La persona seleccionada no existe');
            op.responses['503'] = error(503, 'Configuración o catálogos requeridos para el registro no disponibles');
            op.responses['409'] = error(409,
                'Documento duplicado o selección ambigua',
            );
            op.requestBody = {
                required: true,
                content: json({
                    ...objeto({
                        idPersona: { ...entero, example: 318 },
                        idEspecialista: entero,
                        idUsuario: {
                            type: 'string',
                            pattern: '^[1-9][0-9]{0,18}$',
                        },
                        ci: {
                            type: 'string',
                            maxLength: 30,
                            example: '123456',
                        },
                        complemento: nullable({
                            type: 'string',
                            maxLength: 10,
                        }),
                        nombres: {
                            type: 'string',
                            maxLength: 100,
                            example: 'ANA',
                        },
                        primerApellido: nullable({
                            type: 'string',
                            maxLength: 100,
                            example: 'PEREZ',
                        }),
                        segundoApellido: nullable({
                            type: 'string',
                            maxLength: 100,
                        }),
                        sexo: { type: 'string', enum: ['M', 'F'] },
                        esExtranjero: { type: 'boolean', example: false },
                        fechaNacimiento: {
                            type: 'string',
                            format: 'date',
                            example: '1990-05-12',
                        },
                        idEstadoCivil: entero,
                        idDeptExp: entero,
                        idNacimientoMunicipio: entero,
                        tipoContrato: {
                            type: 'string',
                            enum: ['PERMANENTE', 'EVENTUAL'],
                        },
                        fechaContratoInicio: nullable({
                            type: 'string',
                            format: 'date',
                        }),
                        fechaContratoFin: nullable({
                            type: 'string',
                            format: 'date',
                        }),
                        gradoAcademico: {
                            type: 'string',
                            enum: ['DOCTOR', 'LICENCIADO'],
                        },
                    }),
                    additionalProperties: false,
                    required: [
                        'ci',
                        'nombres',
                        'sexo',
                        'esExtranjero',
                        'fechaNacimiento',
                        'idEstadoCivil',
                        'idDeptExp',
                        'idNacimientoMunicipio',
                        'tipoContrato',
                        'gradoAcademico',
                    ],
                    example: {
                        idPersona: 318,
                        ci: '123456',
                        nombres: 'ANA',
                        primerApellido: 'PEREZ',
                        sexo: 'F',
                        esExtranjero: false,
                        fechaNacimiento: '1990-05-12',
                        idEstadoCivil: 1,
                        idDeptExp: 2,
                        idNacimientoMunicipio: 69,
                        tipoContrato: 'PERMANENTE',
                        gradoAcademico: 'LICENCIADO',
                    },
                }),
            };
        }
    }
    return document;
}

export function configurarSwagger(app: INestApplication) {
    configurarSwaggerAuth(app);

    const config =
        new DocumentBuilder()
            .setTitle(
                'SIGHO · APIs de consulta',
            )
            .setVersion('1.0')
            .setDescription(
                'Inicia sesión en Auth, copia accessToken y pégalo en Authorize. Los IDs de ejemplo deben reemplazarse por registros reales.',
            )
            .addBearerAuth()
            
            .build();


    const document =
        limitarDocumento(
            SwaggerModule.createDocument(
                app,
                config,
                {
                    include: [
                        AuthModule,
                        PacientesModule,
                        PersonasModule,
                        UsuariosModule,
                        EspecialistasModule,
                        SedesModule,
                        FisioterapeutasModule,
                        UsuariosAPIsModule
                    ],

                    deepScanRoutes: false,
                },
            ),
        );


    SwaggerModule.setup(
        'api/docs',
        app,
        document,
        {
            jsonDocumentUrl:
                'api/docs-json',

            swaggerOptions: {
                persistAuthorization: false,
                defaultModelsExpandDepth: -1,
            },

            customSiteTitle:
                'SIGHO · Swagger',

            
            customCss: swaggerHeaderCss + `
                .swagger-ui .topbar { background:#1b1b1b; padding:0; }
                .swagger-ui .topbar > .wrapper { padding:0; max-width:none; }
                .swagger-ui .topbar-wrapper { display:block; max-width:none; }
                .swagger-ui .topbar a{ flex: unset !important; }
                .swagger-ui .topbar a span {padding: 0px !important}
            `,

            customJsStr: `
                (function () {

                    // ======================================
                    // COMPROBAR SESIÓN
                    // ======================================

                    async function comprobarSesion() {

                        try {

                            const response =
                                await fetch(
                                    '/api/docs/session',
                                    {
                                        method: 'GET',

                                        credentials:
                                            'same-origin',

                                        cache:
                                            'no-store',

                                        headers: {
                                            'Cache-Control':
                                                'no-cache'
                                        }
                                    }
                                );


                            if (
                                response.status === 401
                            ) {

                                window.location.replace(
                                    '/api/docs/login'
                                );

                                return false;
                            }


                            if (!response.ok) return false;
                            const session = await response.json();
                            window.swaggerAccountRole = session.rol;
                            if (session.debeCambiarPassword) { window.location.replace('/api/docs/perfil'); return false; }
                            return true;

                        } catch (error) {

                            window.location.replace(
                                '/api/docs/login'
                            );

                            return false;

                        }

                    }


                    // ======================================
                    // BOTÓN CERRAR SESIÓN
                    // ======================================

                    function agregarBotonLogout() {
                        const topbar = document.querySelector('.swagger-ui .topbar .topbar-wrapper');
                        if (!topbar) return;
                        if (!document.getElementById('swagger-shared-header')) {
                            topbar.innerHTML = ${JSON.stringify(swaggerHeader())};
                        }
                        document.getElementById('administrar').hidden = window.swaggerAccountRole !== 'ADMIN';
                        document.getElementById('asignar').hidden = window.swaggerAccountRole !== 'ADMIN';
                    }

                    // ======================================
                    // AL CARGAR
                    // ======================================

                    window.addEventListener(
                        'load',
                        async function () {

                            const valido =
                                await comprobarSesion();

                            if (!valido) {
                                return;
                            }


                            // Swagger tarda un poco
                            // en crear la barra superior.

                            const observer =
                                new MutationObserver(
                                    function () {

                                        agregarBotonLogout();

                                    }
                                );


                            observer.observe(
                                document.body,
                                {
                                    childList: true,
                                    subtree: true
                                }
                            );


                            agregarBotonLogout();

                        }
                    );


                    // ======================================
                    // BOTÓN ATRÁS / ADELANTE
                    // ======================================

                    window.addEventListener(
                        'pageshow',
                        function () {

                            comprobarSesion();

                        }
                    );


                    // ======================================
                    // VOLVER A LA PESTAÑA
                    // ======================================

                    document.addEventListener(
                        'visibilitychange',
                        function () {

                            if (
                                document.visibilityState ===
                                'visible'
                            ) {

                                comprobarSesion();

                            }

                        }
                    );

                })();
            `,
        },
    );

    return document;
}