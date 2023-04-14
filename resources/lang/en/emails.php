<?php
return [
    'general' => [
        'desuscribirme' => 'Unsubscribe',
        'perfil' => 'Update my profile',
        'tyc' => 'Terms & conditions',
    ],
    'registro' => [
        'texto' => 'From now on, you can access your profile by entering your username <span style="color: #8E8063;">@USUARIO</span> and your password.',
        'btn_texto' => 'ENTER THE SITE',
        'imagenes' => [
            'te-damos-la-bienvenida' => 'banner-bienvenida-ingles.png',
            'quedate-atento' => 'novedades-ingles.png',
        ],
        'subject' => 'Welcome',
        'url' => env('APP_URL').'/en'
    ],
    'recuperar' => [
        'linea1' => 'We have received a request to <strong>reset your account password</strong>. If so, please log in again using this one-time password.',
        'linea2' => 'When doing so, do not forget to change it to a new, personal one',
        'linea3' => 'If you did not request a password reset, please ignore this email.',
        'btn_texto' => 'Retrieve your password',
        'imagenes' => [
            'recupera' => 'contraseña-ingles.png'
        ],
        'subject' => 'Magia del Uco - New password'
    ],
    'pedido' => [
        'info' => 'Your purchase information',
        'nro' => 'Your order number is ',
        'producto' => 'Product',
        'fecha' => 'Date',
        'hora' => 'Time',
        'subtotal' => 'Subtotal',
        'envio' => 'Shipping cost',
        'total' => 'Total',
        'infoEnvio' => 'Shipping Information',
        'conocer' => 'You can track the status of your order by logging into your account on our website through the Order List option.',
        'imagenes' => [
            'gracias' => 'compra-ingles.png'
        ],
        'subject' => 'Thank you for choosing us'

    ]
];
