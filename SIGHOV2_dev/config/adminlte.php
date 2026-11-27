<?php

$mostrarActualizacion = false;

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'SIGHO v2',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you {{  }}look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of external google fonts. Disabling the
    | google fonts may be useful if your admin panel internet access is
    | restricted somehow.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => '<b >SIGHO</b> v2',
    'logo_img' => 'vendor/adminlte/dist/img/ssu.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Admin Logo',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration. Currently, two
    | modes are supported: 'fullscreen' for a fullscreen preloader animation
    | and 'cwrapper' to attach the preloader animation into the content-wrapper
    | element and avoid overlapping it with the sidebars and the top navbar.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'vendor/adminlte/dist/img/logosighov2.png',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 600,
            'height' => 400,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => true,
    'usermenu_header_class' => 'bg-default',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-default elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => true,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => 'dashboard',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For detailed instructions you can look the laravel mix section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'menu' => [
        // Navbar items:
        [
            'type' => 'navbar-search',
            'text' => 'Buscar',
            'topnav_right' => true,
        ],
        [
            'type' => 'fullscreen-widget',
            'topnav_right' => true,
        ],
        [
            'text' => 'Notificaciones',
            'url' => 'admin/notificaciones/index',
            'icon' => 'fas fa-fw fa-bell',
        ],
        // Sidebar items:
        [
            'type' => 'sidebar-menu-search',
            'text' => 'Buscar',
        ],
        [
            'text' => 'Panel Principal',
            'url' => '/dashboard',
            // 'can' => 'manage-blog',
        ],
        // [
        //     'text' => 'pages',
        //     'url' => 'admin/pages',
        //     'icon' => 'far fa-fw fa-file',
        //     'label' => 4,
        //     'label_color' => 'success',
        // ],
        ['header' => 'MENÚ'],
        [
            'text' => 'Administración',
            'url' => '',
            'icon' => 'fas fa-fw fa fa-cogs text-info ui-draggable',
            'submenu' => [
                [
                    'text' => 'Clase Aportante',
                    'url' => 'admin/Apo/ClaseAportante/index',
                    'icon' => 'nav-icon far fa fa-list-alt text-default',
                    'can' => 'super-admin-only',
                ],
                [
                    'text' => 'Validar Personas Asuss',
                    'url' => 'admin/ValidarPersonasAsuss/index',
                    'icon' => 'nav-icon far fa fa-users text-default',
                    'can' => 'super-admin-only',
                ],
                [
                    'text' => 'Zonas',
                    'url' => 'admin/zonas/index',
                    'icon' => 'nav-icon far fa fa-globe text-default',
                    'can' => 'menu.zonas',
                ],
                [
                    'text' => 'Unidades',
                    'url' => 'admin/unidades/index',
                    'icon' => 'nav-icon far fa fa-globe text-default',
                    'can' => 'super-admin-only',

                ],
                [
                    'text' => 'Personal SSU',
                    'url' => 'admin/personal_ssu/index',
                    'icon' => 'nav-icon far fa fa-globe text-default',
                    'can' => 'super-admin-only',
                ],
                [
                    'text' => 'Roles y Permisos',
                    'url' => 'admin/RolesPermisos/index',
                    'icon' => 'nav-icon far fa fa-unlock-alt text-default',
                    'can' => 'super-admin-only',
                ],
                [
                    'text' => 'Usuarios',
                    'url' => 'admin/Usuarios/index',
                    'icon' => 'nav-icon far fa fa-user-secret text-default',
                    'can' => 'menu.usuarios',
                ],
                [
                    'text' => 'Videos',
                    'url' => 'admin/videos/index',
                    'icon' => 'nav-icon fas fa-video text-default',
                    'can' => 'super-admin-only',
                ],
                [
                    'text' => 'Imagenes',
                    'url' => 'admin/imagenes/index',
                    'icon' => 'nav-icon fas fa-image text-default',
                    'can' => 'super-admin-only',
                ],
                [
                    'text' => 'Manuales',
                    'url' => 'admin/manuales/index',
                    'icon' => 'nav-icon fas fa-book text-default',
                    'can' => 'super-admin-only',
                ],
                [
                    'text' => 'Pruebas Formales',
                    'url' => 'admin/pruebas-formales/index',
                    'icon' => 'nav-icon fas fa-grimace text-default',
                    'can' => 'super-admin-only',
                ],

            ],
        ],
        [
            'text' => 'Aportes',
            'url' => '#',
            'icon' => 'nav-icon far fa fa-university text-success',
            'submenu' => [
                [
                    'text' => 'Ufv',
                    'url' => 'aportes/Ufv/index',
                    'icon' => 'nav-icon far fa fa-university text-success',
                    'can' => 'menu.ufv',
                ],
                [
                    'text' => 'Tasa de Interes',
                    'url' => 'aportes/TasaInteres/index',
                    'icon' => 'nav-icon far fa fa-university text-success',
                    'can' => 'menu.tasa_interes',
                ],
                [
                    'text' => 'Instituciones',
                    'url' => '#',
                    'icon' => 'nav-icon far fa fa-plus text-success',
                    'can' => 'menu.instituciones',
                    'submenu' => [
                        [
                            'text' => 'Registro - Administración',
                            'url' => 'aportes/Instituciones/index',
                            'icon' => 'nav-icon far fa fa-hospital text-info',
                            'can' => 'menu.instituciones',
                        ],
                        [
                            'text' => 'Certificados',
                            'url' => 'aportes/Instituciones/Certificados/index',
                            'icon' => 'nav-icon far fa fa-book text-info',
                        ],
                    ],
                ],
                [
                    'text' => 'Aportes Institucionales',
                    'url' => '#',
                    'icon' => 'nav-icon far fa fa-plus text-success',
                    'can' => 'menu.aportes_institucionales',
                    'submenu' => [
                        [
                            'text' => 'Planillas de Aportes',
                            'url' => 'aportes/AportesInstitucionales/index',
                            'icon' => 'nav-icon far fa fa-book text-info',
                            'can' => 'menu.aportes_institucionales',

                        ],

                    ],
                ],
                [
                    'text' => 'Voluntarios',
                    'url' => '#',
                    'icon' => 'nav-icon far fa fa-plus text-success',

                    'submenu' => [
                        [
                            'text' => 'Aportantes Voluntarios',
                            'url' => 'aportes/AportantesVoluntarios/index',
                            'icon' => 'nav-icon far fa fa-user-plus text-info',
                            'can' => 'menu.aportantes_voluntarios',

                        ],
                        [
                            'text' => 'Aportes Voluntarios',
                            'url' => 'aportes/AportesVoluntarios/index',
                            'icon' => 'nav-icon far fa fa-address-book text-info',
                            'can' => 'menu.aportes_voluntarios',
                        ],
                    ],
                ],
                [
                    'text' => 'Liquidaciones',
                    'url' => '#',
                    'icon' => 'nav-icon far fa fa-plus text-success',
                    'can' => 'menu.liquidaciones_asuss',
                    'submenu' => [
                        [
                            'text' => 'Asuss',
                            'url' => 'aportes/Liquidaciones/Asuss/index',
                            'icon' => 'nav-icon far fa fa-university text-info',
                            'can' => 'menu.liquidaciones_asuss',

                        ],
                        [
                            'text' => 'Ministerio de Salud',
                            'url' => 'aportes/Liquidaciones/Ministerio/index',
                            'icon' => 'nav-icon far fa fa-university text-info',
                            'can' => 'menu.liquidaciones_ministerio',
                        ],
                    ],
                ],
                [
                    'text' => 'Extractos',
                    'url' => '#',
                    'icon' => 'nav-icon far fa fa-plus text-success',
                    'can' => 'menu.extracto_persona',

                    'submenu' => [
                        [
                            'text' => 'Persona',
                            'url' => 'aportes/Extractos/Personas/index',
                            'icon' => 'nav-icon far fa fa-user text-info',
                            'can' => 'menu.extracto_persona',
                        ],
                        [
                            'text' => 'Instituciones',
                            'url' => 'aportes/Extractos/Instituciones/index',
                            'icon' => 'nav-icon far fa fa-book text-info',
                            'can' => 'menu.extracto_institucion',
                        ],
                    ],
                ],

                [
                    'text' => 'Registro Institución',
                    'url' => 'aportes/Instituciones/Externos/index',
                    'icon' => 'nav-icon far fa fa-plus-square text-danger',
                    'can' => 'menu.instituciones_externas',
                ],
                [
                    'text' => 'Aportes Instituciones',
                    'url' => 'aportes/AportesInstituciones/Externos/index',
                    'icon' => 'nav-icon far fa fa-plus-square text-danger',
                    'can' => 'menu.aportes_externos',
                ],
                [
                    'text' => 'Registro Aviso de Baja',
                    'url' => 'aportes/AvisoBajaInstitucional/index',
                    'icon' => 'nav-icon far fa fa-plus-square text-danger',
                    'can' => 'menu.registro_aviso_baja',
                ],

                [
                    'text' => 'Manuales de Usuario',
                    'url' => 'aportes/Manuales/Usuarios/index',
                    'icon' => 'nav-icon fas fa-tools text-warning',
                    'can' => 'menu.manual_aportes_planillaFormato',
                ],

            ],
        ],

        [
            'text' => 'Afiliacion',
            'url' => '#',
            'icon' => 'fas fa-fw fa fa-users fa-lg text-warning ',
            'submenu' => [
                [
                    'text' => 'Codigo Institucional',
                    'url' => 'afiliacion/AsignacionCodigo/index',
                    'icon' => 'nav-icon far fa fa-building text-info',
                    'can' => 'menu.codigo_institucional',
                ],
                [
                    'text' => 'Titulares',
                    'url' => 'afiliacion/Titulares/index',
                    'icon' => 'nav-icon far fa fa-users text-info',
                    'can' => 'menu.titulares',
                ],
                [
                    'text' => 'Beneficiarios',
                    'url' => 'afiliacion/Beneficiarios/index',
                    'icon' => 'nav-icon far fa fa-users text-info',
                    'can' => 'menu.beneficiarios',
                ],
                [
                    'text' => 'Estudiantes SSUE',
                    'url' => 'afiliacion/Estudiantes/index',
                    'icon' => 'nav-icon far fa fa-address-card text-info',
                    'submenu' => [
                        [
                            'text' => 'Estudiantes SSUE',
                            'url' => 'afiliacion/Estudiantes/index',
                            'icon' => 'nav-icon far fa fa-address-card text-info ml-3',
                            'can' => 'menu.afiliacion_estudiantes_ssue',
                        ],
                        [
                            'text' => 'Usuarios SSUE',
                            'url' => 'afiliacion/Estudiantes/Usuarios/index',
                            'icon' => 'nav-icon fas fa-user-cog text-info ml-3',
                            'can' => 'menu.afiliacion_estudiantes_ssue',
                        ],
                    ],

                ],
                [
                    'text' => 'Afiliados',
                    'url' => 'afiliacion/afiliacion/index',
                    'icon' => 'nav-icon far fa fa-address-book text-info',
                    'can' => 'menu.afiliado_historial',
                ],
                //PARA LA PREAFILIACION
                [
                    'text' => 'Preafiliación',
                    'url' => 'vigencia/Preafiliacion/index',
                    'icon' => 'nav-icon far fa fa-users text-info',
                    'can' => 'menu.preafiliacion',
                ],
                //FIN PREAFILIACION
                //PARA LA ACTUALIZACION DE LOS DATOS

                [
                    'text' => 'Actualizar mis datos',
                    'url' => 'admin/contrastacion/index',
                    'icon' => 'nav-icon far fa fa-users text-info',
                    'can' => 'menu.actualizacion_datos',
                ],

                //FIN ACTUALIZACION DATOS

                [
                    'text' => 'Certificado Afiliación',
                    'url' => '#',
                    'icon' => 'nav-icon far fa fa-plus text-warning',
                    'submenu' => [
                        [
                            'text' => 'Mi Certificado de Afiliación',
                            'url' => 'afiliacion/Certificados/Afiliado/index',
                            'icon' => 'nav-icon far fa fa-users text-info',
                            'can' => 'menu.certificado_afiliado',

                        ],
                        [
                            'text' => 'Cert. Afiliación SSU',
                            'url' => 'afiliacion/Certificados/AfiliacionSSU/index',
                            'icon' => 'nav-icon far fa fa-address-book text-info',
                            'can' => 'menu.certificados_afiliacion',
                        ],
                    ],
                ],
                [
                    'text' => 'Afiliacion SSUE',
                    'url' => '#',
                    'icon' => 'nav-icon far fa fa-plus text-warning',
                    'can' => 'menu.afiliacion_ssue',
                    'submenu' => [
                        [
                            'text' => 'Solicitud',
                            'url' => 'afiliacion/SSUE/afiliacion',
                            'icon' => 'nav-icon far fa fa-clipboard text-info',
                            'can' => 'menu.afiliacion_ssue',
                        ],
                        [
                            'text' => 'Seguimiento',
                            'url' => 'afiliacion/SSUE/seguimiento',
                            'icon' => 'nav-icon far fa fa-eye text-info',
                            'can' => 'menu.afiliacion_seguimiento_ssue',
                        ],
                    ],
                ],
                [
                    'text' => 'Manuales de Usuario',
                    'url' => 'afiliacion/Manuales/Usuarios/index',
                    'icon' => 'nav-icon fas fa-tools text-warning',
                    'can' => [
                        'menu.manual_afiliacion_express',
                        'menu.manual_afiliacion_ssue_nuevos',
                        'menu.manual_afiliacion_ssue_antiguos',
                    ],
                ],
                //    [
                //        'text' => 'Examen Preocupacional',
                //        'url' => '#',
                //        'icon' => 'nav-icon far fa fa-plus text-warning',
                //
                //        'submenu' => [
                //            [
                //                'text' => 'Registrar Solicitud',
                //                'url' => 'afiliacion/ExamenPreocupacional/Instituciones/index',
                //                'icon' => 'nav-icon far fa fa-user-md text-info',
                //                'can' => 'menu.examen_preocupacional',
                //
                //            ],
                //            [
                //                'text' => 'Liquidaciones',
                //                'url' => '#',
                //                'icon' => 'nav-icon far fa fa-user-md text-info',
                //                'can' => 'menu.examen_preocupacional',
                //            ],
                //        ],
                //    ],
                //    [
                //        'text' => 'Examen Médico',
                //        'url' => '#',
                //        'icon' => 'nav-icon far fa fa-plus text-warning',
                //        'submenu' => [
                //            [
                //                'text' => 'Registrar Solicitud',
                //                'url' => 'afiliacion/ExamenMedico/preafiliacion/index',
                //                'icon' => 'nav-icon far fa fa-user-md text-info',
                //                'can' => 'menu.examen_preocupacional',
                //
                //            ],
                //            [
                //                'text' => 'Liquidaciones',
                //                'url' => '#',
                //                'icon' => 'nav-icon far fa fa-user-md text-info',
                //                'can' => 'menu.examen_preocupacional',
                //            ],
                //        ],
                //    ],

            ],
        ],
        [
            'text' => 'Vigencia de Derechos',
            'url' => 'admin/settings',
            'icon' => 'fas fa-fw fa fa-blind fa-lg text-primary',
            'submenu' => [
                [
                    'text' => 'Bajas Institucionales',
                    'url' => 'vigencia/BajasInstitucionales/index',
                    'icon' => 'nav-icon far fa-address-book text-fedault',
                    'can' => 'menu.bajas_asegurados',
                ],
                /*[
                    'text' => 'Avisos de Baja Institucional',
                    'url' =>  'vigencia/AvisosBajaInstitucional/index',
                    'icon' => 'nav-icon far fa fa-file text-fedault',
                    'can' => 'menu.bajas_asegurados',
                ],*/
                [
                    'text' => 'Institución Convenio',
                    'url' => 'vigencia/institucion/index',
                    'icon' => 'nav-icon far fa fa-university text-fedault',
                    'can' => 'menu.institucion_convenio',
                ],
                [
                    'text' => 'Seguros Interior',
                    'url' => 'vigencia/SegurosInterior/index',
                    'icon' => 'nav-icon far fa fa-university text-fedault',
                    'can' => 'menu.seguros_interior',
                ],
                [
                    'text' => 'Prestaciones Interior',
                    'url' => 'vigencia/SolPrestacionesMedicas/index',
                    'icon' => 'nav-icon far fa fa-address-card text-fedault',
                    'can' => 'menu.prestaciones_interior',
                ],
                [
                    'text' => 'Bajas Médicas',
                    'url' => 'vigencia/BajasMedicas/index',
                    'icon' => 'nav-icon far fa fa-file text-fedault',
                    'can' => 'menu.baja_medica_hologacion',
                ],
                [
                    'text' => 'Ordenes de Servicio',
                    'url' => 'vigencia/OrdenesServicio/index',
                    'icon' => 'nav-icon far fa fa-file text-fedault',
                    'can' => 'menu.ordenes_servicio_hologacion',
                ],
                [
                    'text' => 'Convenios',
                    'url' => 'vigencia/Convenios/index',
                    'icon' => 'nav-icon far fa fa-ambulance text-fedault',
                    'submenu' => [
                        [
                            'text' => 'Interior',
                            'url' => 'vigencia/Convenios/Interior/index',
                            'icon' => 'nav-icon far fa fa-hospital text-info',
                            'can' => 'menu.convenios_interior',

                        ],
                        [
                            'text' => 'Emergencias',
                            'url' => 'vigencia/Convenios/Emergencias/index',
                            'icon' => 'nav-icon far fa fa-book text-info',
                            'can' => 'menu.convenios_emergencias',
                        ],
                    ],
                ],
                [
                    'text' => 'Población Asegurada-QR',
                    'url' => 'vigencia/PoblacionAsegurada/index',
                    'icon' => 'nav-icon far fa fa-users text-success',
                    'can' => 'menu.poblacion_asegurada',
                ],

                [
                    'text' => 'Población Asegurada - Busqueda',
                    'url' => 'vigencia/PoblacionAsegurada/busqueda',
                    'icon' => 'nav-icon far fa fa-users text-success',
                    'can' => 'menu.poblacion_asegurada',
                ],

                /*[
                    'text' => 'Prestaciones Médicas',
                    'url' =>  'vigencia/SolPrestacionesMedicas/Externos/index',
                    'icon' => 'nav-icon far fa fa-plus-square text-danger',
                    'can' => 'menu.personal_externo_prestaciones_medicas',
                ],*/

            ],
        ],
        [
            'text' => 'Plataforma',
            'url' => 'admin/settings',
            'icon' => 'fas fa-fw fa-heartbeat fa-lg text-danger',
            'submenu' => [

                // 1. CONFIGURACIÓN MÉDICA
                [
                    'text' => 'Configuración Médica',
                    'icon' => 'fas fa-cogs text-primary',
                    'submenu' => [
                        [
                            'text' => 'Especialidades',
                            'url' => 'plataforma/Especialidades/index',
                            'icon' => 'fas fa-stethoscope text-info ml-3',
                            'can' => 'menu.especialidades',
                        ],
                        [
                            'text' => 'Consultorios',
                            'url' => 'plataforma/Consultorios/index',
                            'icon' => 'fas fa-clinic-medical text-info ml-3',
                            'can' => 'menu.consultorios',
                        ],
                        [
                            'text' => 'Horarios',
                            'url' => 'plataforma/Horarios/index',
                            'icon' => 'fas fa-medkit text-info ml-3',
                            'can' => 'menu.horarios',
                        ],
                    ],
                ],

                // 2. ADMINISTRACIÓN
                [
                    'text' => 'Administración',
                    'icon' => 'fas fa-tools text-primary',
                    'submenu' => [
                        [
                            'text' => 'Feriados',
                            'url' => 'plataforma/AdministracionFeriados/index',
                            'icon' => 'fas fa-gift text-info ml-3',
                            'can' => 'menu.feriados',
                        ],
                        [
                            'text' => 'Comunicados',
                            'url' => 'plataforma/Comunicados/index',
                            'icon' => 'fas fa-bullhorn text-info ml-3',
                            'can' => 'menu.comunicados',
                        ],
                        [
                            'text' => 'Gestionar Agenda',
                            'url' => 'plataforma/GestionarAgenda/index',
                            'icon' => 'fas fa-list text-info ml-3',
                            'can' => 'menu.gestionar_agenda',
                        ],
                        /*[
                            'text' => 'Control Horarios',
                            'url'  => 'plataforma/ControlHorarios/index',
                            'icon' => 'fas fa-clock text-info ml-3',
                            'can'  => 'menu.control_horarios',
                        ],*/
                    ],
                ],

                // 3. ESPECIALISTAS
                [
                    'text' => 'Especialistas',
                    'icon' => 'fas fa-user-md text-primary',
                    'submenu' => [
                        [
                            'text' => 'Gestión de Especialistas',
                            'url' => 'plataforma/Especialistas/index',
                            'icon' => 'fas fa-user-md text-info ml-3',
                            'can' => 'menu.especialistas',
                        ],
                        [
                            'text' => 'Suspensión Vacaciones',
                            'url' => 'plataforma/Especialistas/suspension',
                            'icon' => 'fas fa-briefcase text-info ml-3',
                            'can' => 'menu.suspension_vacaciones',
                        ],
                        [
                            'text' => 'Suspensión Horario',
                            'url' => 'plataforma/SuspensionHorario/index',
                            'icon' => 'fas fa-hourglass-end text-info ml-3',
                            'can' => 'menu.suspension_horarios',
                        ],
                        [
                            'text' => 'Agenda Especialista',
                            'url' => 'plataforma/AgendaEspecialista/index',
                            'icon' => 'fas fa-calendar-plus text-info ml-3',
                            'can' => 'menu.agenda_especialista',
                        ],

                    ],
                ],

                // 4. AGENDAMIENTO
                [
                    'text' => 'Agendamiento',
                    'icon' => 'fas fa-calendar-alt text-primary',
                    'submenu' => [
                        [
                            'text' => 'Agenda Plataforma',
                            'url' => 'plataforma/Agendamiento/Plataforma/index',
                            'icon' => 'fas fa-hospital text-info ml-3',
                            'can' => 'menu.agenda_plataforma',
                        ],

                        [
                            'text' => 'Agendamiento Web',
                            'url' => 'plataforma/Agendamiento/Web/index',
                            'icon' => 'fas fa-user-plus text-info ml-3',
                            'can' => 'menu.agenda_web',
                        ],
                        [
                            'text' => 'Historial Citas',
                            'url' => 'plataforma/HistorialCitas/index',
                            'icon' => 'fas fa-history  text-info ml-3',
                            'can' => 'plataforma.historial-citas.index',
                        ],
                        [
                            'text' => 'Anulación de Fichas',
                            'url' => 'plataforma/Anulaciones/index',
                            'icon' => 'fas fa-user-times text-info ml-3',
                            'can' => 'menu.anulacion_fichas',
                        ],
                    ],
                ],
                [
                    'text' => 'Agendamiento Ecografia',
                    'icon' => 'fas fa-x-ray text-info',
                    'submenu' => [
                        [
                            'text' => 'Agenda Ecografia',
                            'url' => 'plataforma/Agendamiento/Ecografia/AgendaEcografia/index',
                            'icon' => 'fas fa-calendar-check text-warning ml-3',
                            'can' => 'menu.agenda_ecografia',
                        ],
                        [
                            'text' => 'Anulación de Fichas',
                            'url' => 'plataforma/Agendamiento/Ecografia/AnulacionEcografia/index',
                            'icon' => 'fas fa-calendar-times text-warning ml-3',
                            'can' => 'menu.anulacion_ecografia',
                        ],
                        [
                            'text' => 'Suspensión Horario Ecografia',
                            'url' => 'plataforma/Agendamiento/Ecografia/SuspensionHorarioEcografia/index',
                            'icon' => 'fas fa-calendar-times text-warning ml-3',
                            'can' => 'menu.suspension_ecografia',
                        ],
                    ],
                ],
            ],
        ],
        [
            'text' => 'Archivo Clínico',
            'url' => '',
            'icon' => 'fas fa-hospital-user text-info ',
            'submenu' => [
                [
                    'text' => 'Agendas Diarias',
                    'url' => 'ArchivoClinico/ArchivoClinico/index',
                    'icon' => 'fas fa-notes-medical text-info ml-3',
                    'can' => 'menu.agendas_diarias',
                ],
                [
                    'text' => 'Lista Agendas',
                    'url' => 'ArchivoClinico/ListaAgenda/index',
                    'icon' => 'fas fa-book text-info ml-3',
                    'can' => 'menu.lista_agenda_archivo_clinico',
                ],

            ],
        ],
        [
            'text' => 'Atención médica',
            'url' => 'admin/settings',
            'icon' => 'fas fa-fw fa-user-md fa-lg text-primary',
            // 'fa-user-md' es el doctor, ideal para "Atención"
            'submenu' => [
                [
                    'text' => 'Mis Recetas',
                    // resources/views/AtencionMedica/MisRecetas/index.blade.php
                    'url' => 'atencion_medica/MisRecetas/index',
                    'icon' => 'fas fa-fw fa-file-medical text-success ml-3',
                    'can' => 'menu.mis_recetas',
                    // 'fa-file-medical' representa una receta o historial
                ],
                /* [
                    'text' => 'Historial Clínico',
                    'url'  => 'admin/settings/history',
                    'icon' => 'fas fa-fw fa-notes-medical text-info',
                ], */
            ],
        ],
        [
            'text' => 'Actualización de datos',
            'url' => 'admin/actualizar_datos/index',
            'icon' => 'fas fa-pen-square fa-lg text-warning',
            'can' => 'menu.actualizacion_datos_afiliacion',
        ],

        [
            'text' => 'change_password',
            'url' => 'admin/cambiocontrasenia/index',
            'icon' => 'fas fa-fw fa-lock',
        ],

        // [
        //     'text' => 'multilevel',
        //     'icon' => 'fas fa-fw fa-share',
        //     'submenu' => [
        //         [
        //             'text' => 'level_one',
        //             'url' => '#',
        //         ],
        //         [
        //             'text' => 'level_one',
        //             'url' => '#',
        //             'submenu' => [
        //                 [
        //                     'text' => 'level_two',
        //                     'url' => '#',
        //                 ],
        //                 [
        //                     'text' => 'level_two',
        //                     'url' => '#',
        //                     'submenu' => [
        //                         [
        //                             'text' => 'level_three',
        //                             'url' => '#',
        //                         ],
        //                         [
        //                             'text' => 'level_three',
        //                             'url' => '#',
        //                         ],
        //                     ],
        //                 ],
        //             ],
        //         ],
        //         [
        //             'text' => 'level_one',
        //             'url' => '#',
        //         ],
        //     ],
        // ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@11',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => true,
];
