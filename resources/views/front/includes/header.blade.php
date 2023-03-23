<header class="header header_JS" >


  <nav class="navbar navbar-expand-xl bg-transparent fixed-top">
    <div class="container-fluid">
      <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
        <div id="nav-lines">
          <svg viewbox="0 0 64 64">
            <line id="nav-line-1" x1="8" x2="40" y1="16" y2="16" class="nav-line" />
            <line id="nav-line-2" x1="8" x2="56" y1="32" y2="32" class="nav-line" />
            <line id="nav-line-3" x1="8" x2="56" y1="48" y2="48" class="nav-line" />

            <line x1="16" x2="48" y1="16" y2="48" class="cross-line" />
            <line x1="16" x2="48" y1="48" y2="16" class="cross-line" />


            <rect class="rect" width="42" height="42" x="11" y="11" />
          </svg>
        </div>
      </button>
      <a class="navbar-brand" href="{{route('home',[app()->getLocale()])}}"><img src="{{asset('img/logo.svg')}}" /></a>
      <div class="collapse navbar-collapse" id="navbarTogglerDemo01">

        <ul class="nav-lenguage mobile">
          <li><a href="{{route('cambiarIdioma',['es',\Route::currentRouteName()])}}" class="{{app()->getLocale() === 'es' ? 'active' : ''}}">ESP</a></li>
          <li><a href="{{route('cambiarIdioma',['en',\Route::currentRouteName()])}}" class="{{app()->getLocale() === 'en' ? 'active' : ''}}">ENG</a></li>
          <li><a href="{{route('cambiarIdioma',['pt',\Route::currentRouteName()])}}" class="{{app()->getLocale() === 'pt' ? 'active' : ''}}">POR</a></li>

        </ul>
        <!--a class="nav-link mobile comprar"  href="javascript:void(0);" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><img src="{{asset('img/shop.svg')}}" class="icon icon-shop" />{{trans('front.navMenuFooter.links.comprar')}}</a-->

        <ul class="navbar-nav ">

          <li class="nav-item">
            <a class="nav-link" href="{{routeIdioma('legado')}}">{{trans('front.navMenuFooter.links.legado')}}</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="{{routeIdioma('nuestroCompromiso')}}">{{trans('front.navMenuFooter.links.nuestroCompromiso')}}</a>
          </li>

          <li class="nav-item has-submenu">
            <a class="nav-link" href="javascript:void(0);">{{trans('front.navMenuFooter.links.colecciones')}}</a>

            <div class="submenu">
              <div class="images">
                <img src="{{asset('img/vinos/vino-1.png')}}" class="img-prod" />
                <img src="{{asset('img/vinos/vino-2.png')}}" class="img-prod" />
              </div>
              <div class="menu">
                <ul>
                  <li><a href="{{routeIdioma('colecciones')}}">{{trans('front.navMenuFooter.links.colecciones')}}</a></li>
                    <!--li><a href="{{routeIdioma('colecciones.tucci')}}">{{trans('front.navMenuFooter.links.francescaTucci')}}</a></li-->
                  <li><a href="{{routeIdioma('colecciones.interwine')}}">{{trans('front.navMenuFooter.links.interwine')}}</a></li>
                </ul>
              </div>
            </div>


          </li>

          <li class="nav-item">
            <a class="nav-link" href="{{routeIdioma('francescaTucci')}}">{{trans('front.navMenuFooter.links.francescaTucci')}}</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="{{routeIdioma('novedades')}}">{{trans('front.navMenuFooter.links.novedades')}}</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="{{routeIdioma('contacto')}}">{{trans('front.navMenuFooter.links.contacto')}}</a>
          </li>

        </ul>

        <ul class="small-links">
          <li><a href="{{routeIdioma('home')}}">{{trans('front.navMenuFooter.links.pp')}}</a></li>
          <li><a href="{{routeIdioma('home')}}">{{trans('front.navMenuFooter.links.tyc')}}</a></li>
          <li><a href="{{routeIdioma('home')}}">{{trans('front.navMenuFooter.links.lcr')}}</a></li>
          <li><a href="{{routeIdioma('home')}}">{{trans('front.navMenuFooter.links.dc')}}</a></li>
        </ul>

      </div>
      <div class="nav-right">
        <ul class="nav-lenguage desktop">
          <li><a href="{{route('cambiarIdioma',['es',\Route::currentRouteName()])}}" class="{{app()->getLocale() === 'es' ? 'active' : ''}}">ESP</a></li>
          <li><a href="{{route('cambiarIdioma',['en',\Route::currentRouteName()])}}" class="{{app()->getLocale() === 'en' ? 'active' : ''}}">ENG</a></li>
          <li><a href="{{route('cambiarIdioma',['pt',\Route::currentRouteName()])}}" class="{{app()->getLocale() === 'pt' ? 'active' : ''}}">POR</a></li>
          @if(!auth()->guard('web')->check())
          <li><a href="{{routeIdioma('login')}}" >{{trans('front.navMenuFooter.links.ingresar')}}</a></li>
          @else
          <li><a href="javascript:void(0);" data-bs-toggle="offcanvas" data-bs-target="#menuUser" aria-controls="menuUser"><img src="{{asset('img/user.svg')}}" class="icon icon-user" /></a></li>

          @endif
            <li>
                <a href="javascript:void(0);" class="cart-link" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight" >
                    <span class="cart-count" v-if="carrito.cantidad > 0">(%carrito.cantidad%)</span>
                    <img src="{{asset('img/shop.svg')}}" class="icon icon-shop" style="width:14px; padding: 0 0 2px 0;" />
                </a>
            </li>

        </ul>
        <a href="javascript:void(0);" class="cart-link  d-xl-none" style="right: 60px;" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight" >
            <span class="cart-count" v-if="carrito.cantidad > 0">(%carrito.cantidad%)</span>
            <img src="{{asset('img/shop.svg')}}" class="icon icon-shop" style="width:20px; padding: 0 0 2px 0;" />
        </a>

        @if(!auth()->guard('web')->check())
        <a href="{{routeIdioma('login')}}" class="user-link d-xl-none" style="right: 20px;"><img src="{{asset('img/user.svg')}}" class="icon icon-user" /></a>
        @else
        <a href="javascript:void(0);" class="user-link d-xl-none" style="right: 20px;" data-bs-toggle="offcanvas" data-bs-target="#menuUser" aria-controls="menuUser"><img src="{{asset('img/user.svg')}}" class="icon icon-user" /></a>

        @endif

        <!-- <a class="nav-link d-none d-xl-flex"  href="#">INGRESAR</a> -->
        <a class="nav-link d-none"  ><img src="{{asset('img/shop.svg')}}" class="icon icon-shop" />{{trans('front.navMenuFooter.links.comprar')}}</a>
      </div>
    </div>
  </nav>

</header>
