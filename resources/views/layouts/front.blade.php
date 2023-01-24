<!DOCTYPE html>
<html lang="en">
    <head>

        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>{{ isset($tituloPagina) ? $tituloPagina : 'Home' }} | {{env('APP_NAME')}}</title>
        <meta property="og:title" content="Magia de Uco" />
        <meta property="og:image" content="" />
        <!-- Favicon-->
        <link rel="icon" type="image/jpg" href="" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="{{asset('css/libraries/bootstrap.min.css')}}" rel="stylesheet" />
        <!-- Favicons -->
        <!--link href="{{asset('img/favicon/favicon.ico')}}" rel="shortcut icon" /-->
        <link rel="mask-icon" href="{{asset('img/favicon/safari-pinned-tab.svg')}}" color="#000000">
        <meta name="msapplication-TileColor" content="#000000">
        <meta name="theme-color" content="#000000">

        <script src="https://kit.fontawesome.com/ed5055d841.js" crossorigin="anonymous"></script>

        <link
        rel="stylesheet"
        href="{{asset('css/libraries/animate.min.css')}}"/>
        <link rel="stylesheet" href="{{asset('css/libraries/slick.css')}}" />
        <link rel="stylesheet" href="{{asset('css/libraries/slick-theme.css')}}" />
        <link rel="stylesheet" href="{{asset('css/libraries/jquery.fancybox.min.css')}}" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('vendor/iziToast/iziToast.min.css')}}" integrity="" crossorigin="anonymous" />
        <link rel="stylesheet" href="{{asset('vendor/intlTellnput/css/intlTelInput.css')}}">
        <!-- Custom styles for this template -->
        <link href="{{asset('css/style.css')}}?v=1.11{{uniqid()}}" rel="stylesheet">
        <style>
          /*UTILS*/
          [v-cloak] {display: none!important;}
          [v-cloak] > * { display:none!important; }
          [v-cloak]::before { content: "loading…" }

          [v-cloak] .v-cloak--block {
            display: block!important;
          }

          [v-cloak] .v-cloak--inline {
            display: inline!important;
          }

          [v-cloak] .v-cloak--inlineBlock {
            display: inline-block!important;
          }

          [v-cloak] .v-cloak--hidden {
            display: none!important;
          }

          [v-cloak] .v-cloak--invisible {
            visibility: hidden!important;
          }

          .v-cloak--block,
          .v-cloak--inline,
          .v-cloak--inlineBlock {
            display: none!important;
          }
        </style>
        <!-- Global site tag (gtag.js) - Google Analytics -->

        @yield('css')
        <script type="text/javascript">
          var _csrfToken = '{!! csrf_token() !!}';
          var _methods = {};
          var _components = {};
          var _dictionary = {
            es: {
              messages: {
                _default: 'El campo no es válido.',
                required: 'El campo es obligatorio.',
                email: 'El campo debe ser un correo electrónico válido.',
                regex: 'El formato ingresado es incorrecto'
              },
              custom: {
                password: {
                  confirmed: 'Las contraseñas ingresadas no coinciden',
                }
              }
            }
          };
          var _generalData = {
              trans: {!! json_encode(trans('front')) !!},
              alert: {
                  show: false,
                  type: '',
                  title: '',
                  message: ''
              },
              carrito: {
                items: {!! json_encode(\Cart::getContent()) !!},
                cantidad: {{\Cart::getTotalQuantity()}},
                total: {{\Cart::getTotal(2,'.','')}},
                url_agregar: '{{routeIdioma('carrito.agregar')}}',
                url_quitar: '{{routeIdioma('carrito.quitar',['_ID_'])}}',
                url_index: '{{routeIdioma('carrito')}}',
                agregando: false,
                quitando: false,
                item: {id: null, cantidad: 0}
              },
              checkout: {
                url_confirmar: '{{routeIdioma('checkout.confirmar')}}'
              },
              usuario: {!! \Auth::check() ? json_encode(Arr::only(\Auth::user()->toArray(),['nombre','apellido','id'])) : 'null' !!},
              locale: '{!! app()->getLocale()!!}',
              newsletter: {
                url_post_save: '{{route("service.newsletter.guardar")}}',
                loading: false,
                form: {
                  email: null,
                  submitted: false
                }
              }
          };
          var _mounted = [];

        </script>

    </head>
    @php
      if (!isset($dataSection)) {
        $dataSection = '';
      }
    @endphp
    <body data-section="<?= $dataSection; ?>" class="">

      <div id="app" style="display: contents;" v-cloak>
        @include('front.modules.modal-edad')
        @include('front.includes.header')
        @yield('content')
        @include('front.includes.footer')
      </div>

      <script type="module" src="{{asset('js/js.cookie.js')}}"></script>
      <script src="{{asset('js/jquery-3.5.1.min.js')}}"></script>
      <script src="{{asset('js/jquery-ui.js')}}"></script>
      <script src="{{asset('js/jquery.ui.touch-punch.min.js')}}"></script>

      <script src="{{asset('js/popper.min.js')}}" ></script>
      <script src="{{asset('js/bootstrap.min.js')}}" ></script>

      <!-- libraries -->
      <script type="text/javascript" src="{{asset('js/slick.min.js')}}"></script>
      <script src="{{asset('js/jquery.fancybox.min.js')}}"></script>

      <script src="{{asset('js/global.js')}}?v=1.0832"></script>
      <script src="{{asset('js/scripts/header.js')}}?v=1.011114"></script>
      <script src="{{asset('js/scripts/menuToggle.js')}}?v=1.02"></script>
      <script src="{{asset('js/scripts/fadeChildren.js')}}?v=1.03"></script>
      <script src="{{asset('js/isotope-docs.min.js')}}"></script>
      <script src="{{asset('js/packery-mode.pkgd.min.js')}}"></script>
      <script src="{{asset('js/sectionScroll.js')}}?v=0.<?php echo(uniqid());?>"></script>
      <script src="{{ asset('vendor/intlTellnput/intlTelInput.js') }}"></script>



      <script src="{{ asset('vendor/iziToast/iziToast.min.js') }}"></script>
      <script src="{{ asset('vendor/lodash.min.js') }}"></script>
      <script src="{{ asset('vendor/moment/moment.min.js') }}"></script>
      <script src="{{ asset('vendor/vue.js') }}"></script>
      <script src="{{ asset('vendor/vee-validate.min.js') }}"></script>
      <script src="{{ asset('vendor/vue-resource.min.js') }}"></script>
      <script src="{{asset('js/app.js')}}?v=1"></script>
      @yield('scripts')
      <script src="{{ asset('js/template.js') }}"></script>

    </body>
</html>
