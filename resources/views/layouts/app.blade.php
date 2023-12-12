<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="shortcut icon" href="{{asset('admin/img/favicon-32x32.png')}}" type="image/png" sizes="16x16"/>


    @if (Auth::check('admin'))
        <link rel="stylesheet" href="{{ asset('vendor/bootstrap-3.3.7/css/bootstrap.min.css') }}">
        <!--link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous"-->
        <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/css/styles.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/css/utils.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/AdminLTE.min.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/css/skin-adminlte.css') }}">

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
                    alert: {
                        show: false,
                        type: '',
                        title: false,
                        message: ''
                    },
                    lang: {!! json_encode( trans('admin') ) !!}
                };
                var _computeds = {};
                var _mounted = [];
        </script>

    @else
        <!-- JS DE FRONT -->
    @endif
    @yield('css')
</head>

<body class="skin-adminlte-light sidebar-mini">
@if (Auth::check('admin'))
    <div class="wrapper">
        <!-- Main Header -->
        <header class="main-header">

            <!-- Logo -->
            <!--a href="#" class="logo">

            </a-->

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <i class="fas fa-bars"></i><span class="sr-only">Toggle navigation</span>
                </a>

            </nav>
        </header>

        <!-- Left side column. contains the logo and sidebar -->
        @include('layouts.sidebar')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper"  id="app" v-cloak>
            @yield('content-header')
            <div class="clearfix"></div>
            <div v-show="alert.show" class="alert alert-dismissible m-20" style="margin: 10px;" :class="{'alert-danger': alert.type == 'E','alert-warning': alert.type == 'W','alert-info': alert.type == 'I','alert-success': alert.type == 'S'}">
                    <button type="button" class="close" @click="alert.show = false;">×</button>
                    <h4 v-if="alert.title">(% alert.title %)!</h4>
                    <i class="icon fa" :class="{'fa-ban': alert.type == 'E','fa-exclamation-triangle': alert.type == 'W','fa-info': alert.type == 'I','fa-check': alert.type == 'S'}"></i> (% alert.message %)
            </div>
            @yield('content')
        </div>


        <!-- Main Footer -->
        <footer class="main-footer" style="max-height: 100px;text-align: center">
            <strong>Copyright © 2017. All rights reserved.</strong>
        </footer>

    </div>
@else
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{!! url('/') !!}">
                    Abra Auto
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a href="{!! url('/home') !!}">Home</a></li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    <li><a href="{!! url('/login') !!}">Login</a></li>
                    <li><a href="{!! url('/register') !!}">Register</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
@endif

    <!-- jQuery 3.1.1 -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

    @if (Auth::check('admin'))
    <!--script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script-->
        <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.15/lodash.min.js"></script>

        <!--script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script-->
        <!--script src="//cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script-->
        <script src="{{ asset('vendor/bootstrap-3.3.7/js/bootstrap.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset('vendor/adminlte/js/app.min.js') }}"></script>
        <script src="{{ asset('vendor/vue.js') }}"></script>
        <script src="{{ asset('vendor/vue-cookies.js') }}"></script>
        <script src="{{ asset('vendor/vue-resource.min.js') }}"></script>
        <script src="{{ asset('vendor/vue-strap.min.js') }}"></script>
        <script src="{{ asset('vendor/moment/moment.min.js') }}"></script>
        <script src="{{ asset('admin/js/button-type.js') }}?v=1.1"></script>


    @else
    @endif

    @yield('scripts')
    <script src="{{ asset('admin/js/template.js') }}"></script>


</body>
</html>
