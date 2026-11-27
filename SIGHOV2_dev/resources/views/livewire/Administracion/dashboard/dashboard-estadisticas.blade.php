<div>
    @can('super-admin-only')
        @livewire('Administracion.dashboard.administracion.usuarios-dashboard')
    @endcan
    @can('super-admin-only')
        @livewire('Administracion.dashboard.aportes.aportes-dashboard-main')
    @endcan

    @can('super-admin-only')
        @livewire('Administracion.dashboard.afiliacion.afiliacion-dashboard-main')
    @endcan

    @can('super-admin-only')
        @livewire('Administracion.dashboard.vigencia.vigencia-dashboard-main')
    @endcan

    @can('super-admin-only')
        @livewire('Administracion.dashboard.plataforma.plataforma-dashboard-main')
    @endcan

    <!-- vista de asegurados afiliados rol pasado: index.informaciones-->
    @can('super-admin-only')
        @livewire('Administracion.dashboard.comunicados.comunicados-dashboard-main')
    @endcan
    <br>
    @can('menu.agenda_web')
        @livewire('Administracion.dashboard.plataforma.agenda-asegurado')
    @endcan
    <br> <br>
</div>

<!-- Modificar boostrap 5 a 4 -->
