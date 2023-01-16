<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <title>Mailing - Microsoft Ruta365</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      html,
      body {
        height: 100%;
      }

      body {
        display: -ms-block;
        display: block;
        align-items: center;
        padding-top: 0px;
        padding-bottom: 0px;
        background-color: #fff; /*#00a1df;*/
        font-family: 'Lato', sans-serif;
      }

      .container {
        max-width: 600px;
        background-color: #00a1df;
        padding-bottom: 30px;
      }

      .container-fluid p {
        margin-top: 13px;
        margin-bottom: 13px;
      }

      .container .h1 {
        color: #fff;
        font-size: 27px;
        font-weight: bold !important;
        padding-top: 50px;
      }

      .container .p {
        color: #fff;
        font-size: 15px;
        line-height: 17px;
        padding-bottom: 15px;
      }

      .container .btn-002 {
        width: 230px;
        margin-top: 40px;
        margin-bottom: 40px;
        background-color: #000000;
        color:#fff;
        border-radius: 0;
        font-size: 11pt;
        margin-left: auto;
        margin-right: auto;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
  </head>
  <body class="text-center">
    <div class="container-fluid">
      @if(!isset($respaldo))
      <p class="p font-weight-normal">Si no puede visualzar este newsletter correctamente, <a href="{{route('mailingRespaldo.registro',[md5($registrado->id)])}}">haga click aquí</a>.</p>
      @endif
      <img src="{{asset('img/Header-mailing.jpg')}}" class="img-fluid" alt="Microsoft Ruta 365">
    </div>
    <div class="container">

      <h1 class="h1 mb-3 font-weight-normal">Tu cuenta está casi lista</h1>
      <p class="p mb-3 font-weight-normal">Hola {{$registrado->nombre}}, gracias por haberte registrado.<br>Para activar tu cuenta, haz click en el botón de verificar email.<br>¡Muchas gracias!</p>

      <a href="{{route('confirmarCuenta',[md5($registrado->id)])}}" class="link-001" target="_self"><div class="btn btn-lg btn-002 btn-block">Verificar email</div></a>

      <p class="p mb-3 font-weight-normal">Si usted no se suscribió a este sitio, por favor descarte este email.</p>
    </div>
</body>
</html>