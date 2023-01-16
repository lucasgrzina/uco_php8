<div class="offcanvas offcanvas-end" tabindex="-1" id="menuUser" aria-labelledby="menuUserLabel">
  <div class="offcanvas-header">
    <h5 id="menuUserLabel"></h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <div class="user-menu">
      <h4>{{trans('front.navMenuFooter.links.menuUsuario.bienvenidos')}}</h4>
      <ul class="nav-offcanvas">
        <li><a href="{{routeIdioma('miCuenta.pedidos')}}" >{{trans('front.navMenuFooter.links.menuUsuario.misPedidos')}} <img src="{{asset('img/shop.svg')}}" class="icon icon-shop" /></a></li>
        <li><a href="{{routeIdioma('miCuenta.misDatos')}}">{{trans('front.navMenuFooter.links.menuUsuario.miCuenta')}} <img src="{{asset('img/user.svg')}}" class="icon icon-user" /></a></li>
        <!--li><a href="{{routeIdioma('miCuenta.cambiarPassword')}}">{{trans('front.navMenuFooter.links.menuUsuario.cambiarPassword')}} <img src="{{asset('img/user.svg')}}" class="icon icon-user" /></a></li-->
        <li><a href="{{routeIdioma('logout')}}">{{trans('front.navMenuFooter.links.menuUsuario.salir')}} <!--img src="{{asset('img/user.svg')}}" class="icon icon-user" /--></a></li>
      </ul>
    </div>
    
  </div>
</div>