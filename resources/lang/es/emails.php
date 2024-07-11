<?php
return [
    'general' => [
        'desuscribirme' => 'Quiero desuscribirme',
        'perfil' => 'Actualizar perfil',
        'tyc' => 'Términos y condiciones',
    ],
    'registro' => [
        'texto' => 'A partir de ahora, para acceder a tu perfil, podrás hacerlo ingresando tu nombre de usuario <span style="color: #CDCFAD;">@USUARIO</span> y tu contraseña.',
        'btn_texto' => 'INGRESÁ AL SITIO',
        'imagenes' => [
            'te-damos-la-bienvenida' => 'header-bienvenida-esp.jpg',
            'quedate-atento' => 'novedades-esp.jpg',
        ],
        'subject' => 'Bienvenido',
        'url' => env('APP_URL').'/es'
    ],
    'recuperar' => [
        'linea1' => 'Hemos recibido una solicitud para <strong>restablecer la contraseña</strong> de tu cuenta. Si es así, ingresá de nuevo utilizando esta por única vez; ',
        'linea2' => 'Al hacerlo no olvides cambiarla por una <strong>nueva y personal</strong>.',
        'linea3' => 'Si no solicitaste el restablecimiento de la contraseña, ignorá este correo electrónico.',
        'btn_texto' => 'RESTABLECER CONTRASE&Ntilde;A',
        'imagenes' => [
            'recupera' => 'contrasena-esp.jpg'
        ],
        'subject' => 'Magia de Uco - Nueva clave'
    ],
    'pedido' => [
        'info' => 'Información de tu compra',
        'nro' => 'Tu número de pedido es ',
        'producto' => 'Producto',
        'fecha' => 'Fecha',
        'hora' => 'Hora',
        'subtotal' => 'Subtotal',
        'envio' => 'Costo de envío',
        'total' => 'Total',
        'infoEnvio' => 'Información del envío',
        'conocer' => 'Para conocer el estado de tu pedido podés hacerlo ingresando a <strong><a style="color:#000!important;" href="_lnk_cuenta_">tu cuenta</a></strong> en nuestro sitio web a través de la opción listado de pedidos.',
        'imagenes' => [
            'gracias' => 'header-registro-2.jpg'
        ],
        'subject' => 'Gracias por elegirnos'
    ],
    'confirmarPass' => [
        'linea1' => '¡Tu cambio de contraseña<br>se ha realizado con éxito!',
        'linea2' => 'Para ver o cambiar información adicional podés <a style="max-width: 350px; width: 100%; font-family: Helvetica, Arial, sans-serif; font-size:11px; line-height:16px; text-align:left; color:#CDCFAD!important" href="'.env('APP_URL').'/es'.'">ingresar a tu cuenta.</a>',
        'linea3' => 'Si usted no ha solicitado este cambio de contraseña, por favor, desestime este correo electrónico',
        'linea4' => '¡Muchas gracias por confiar<br>en Magia de Uco!',
        'imagenes' => [
            'recupera' => 'recup-contrasena-esp.jpg'
        ],
        'subject' => 'Magia de Uco - Contraseña actualizada'
    ]
];
