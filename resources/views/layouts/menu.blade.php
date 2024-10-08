<li class="{{ Request::is('dashboard*') ? 'active' : '' }}">
  <a href="{!! route('admin.home') !!}"><i class="fa fa-chevron-right"></i><span>Dashboard</span></a>
</li>
@if (\App\Helpers\AdminHelper::mostrarMenu(['usuarios','roles-y-permisos','clientes']))
<li class=" treeview menu-open {{ Request::is('users*') || Request::is('roles*') || Request::is('clientes*') ? 'active' : '' }}">
  <a href="#">
    <i class="fa fa-user-shield"></i> <span>Administración</span>
    <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>
  <ul class="treeview-menu" style="">
    @if (\App\Helpers\AdminHelper::mostrarMenu('usuarios'))
    <li class="{{ Request::is('usuarios*') ? 'active' : '' }}">
      <a href="{!! route('usuarios.index') !!}"><i class="fa fa-user"></i><span>Usuarios - Staff</span></a>
    </li>
    @endif
    @if (\App\Helpers\AdminHelper::mostrarMenu('roles-y-permisos'))
    <li class="{{ Request::is('roles*') ? 'active' : '' }}">
      <a href="{!! route('roles.index') !!}"><i class="fa fa-user"></i><span>Roles</span></a>
    </li>
    @endif
  </ul>
</li>
@endif

@if (\App\Helpers\AdminHelper::mostrarMenu('registrados'))
<li class="{{ Request::is('registrados*') ? 'active' : '' }}">
    <a href="{!! route('registrados.index') !!}"><i class="fa fa-edit"></i><span>Registrados</span></a>
</li>
@endif

@if (\App\Helpers\AdminHelper::mostrarMenu('legados'))
<li class="{{ Request::is('legados*') ? 'active' : '' }}">
    <a href="{!! route('legados.index') !!}"><i class="fa fa-edit"></i><span>Legados</span></a>
</li>
@endif

@if (\App\Helpers\AdminHelper::mostrarMenu('nuestro-compromiso'))
<li class="{{ Request::is('nuestro-compromiso*') ? 'active' : '' }}">
    <a href="{!! route('nuestro-compromiso.index') !!}"><i class="fa fa-edit"></i><span>Nuestro compromiso</span></a>
</li>
@endif

@if (\App\Helpers\AdminHelper::mostrarMenu('notas'))
<li class="{{ Request::is('notas*') ? 'active' : '' }}">
    <a href="{!! route('notas.index') !!}"><i class="fa fa-edit"></i><span>Notas</span></a>
</li>
@endif
@if (\App\Helpers\AdminHelper::mostrarMenu('sliders'))
<li class="{{ Request::is('home-sliders*') ? 'active' : '' }}">
    <a href="{!! route('home-sliders.index') !!}"><i class="fa fa-edit"></i><span>Sliders</span></a>
</li>
@endif

@if (\App\Helpers\AdminHelper::mostrarMenu('newsletters'))
<li class="{{ Request::is('newsletters*') ? 'active' : '' }}">
    <a href="{!! route('newsletters.index') !!}"><i class="fa fa-edit"></i><span>Newsletters</span></a>
</li>
@endif

@if (\App\Helpers\AdminHelper::mostrarMenu('contactos'))
<li class="{{ Request::is('contactos*') ? 'active' : '' }}">
    <a href="{!! route('contactos.index') !!}"><i class="fa fa-edit"></i><span>Contactos</span></a>
</li>
@endif

@if (\App\Helpers\AdminHelper::mostrarMenu('vinos'))
<li class="{{ Request::is('vinos*') ? 'active' : '' }}">
    <a href="{!! route('vinos.index') !!}"><i class="fa fa-edit"></i><span>Vinos</span></a>
</li>
@endif

@if (\App\Helpers\AdminHelper::mostrarMenu('pedidos'))
<li class="{{ Request::is('pedidos*') ? 'active' : '' }}">
    <a href="{!! route('pedidos.index') !!}"><i class="fa fa-edit"></i><span>Pedidos</span></a>
</li>
@endif

@if (\App\Helpers\AdminHelper::mostrarMenu('packagings'))
<li class="{{ Request::is('packagings*') ? 'active' : '' }}">
    <a href="{!! route('packagings.index') !!}"><i class="fa fa-edit"></i><span>Packagings</span></a>
</li>
@endif
<!--li class="{{ Request::is('pais*') ? 'active' : '' }}">
    <a href="{!! route('pais.index') !!}"><i class="fa fa-edit"></i><span>Pais</span></a>
</li-->
@if (\App\Helpers\AdminHelper::mostrarMenu('configuraciones'))
<li class="{{ Request::is('configuraciones*') ? 'active' : '' }}">
    <a href="{!! route('configuraciones.index') !!}"><i class="fa fa-edit"></i><span>Configuraciones</span></a>
</li>
@endif
