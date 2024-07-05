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
            'te-damos-la-bienvenida' => 'header-bienvenida-eng.jpg',
            'quedate-atento' => 'novedades-eng.jpg',
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
            'recupera' => 'contrasena-eng.jpg'
        ],
        'subject' => 'Magia de Uco - New password'
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
        'conocer' => 'You can track the status of your order by logging into <a style="color:#000!important;" href="_lnk_cuenta_">your account</a> on our website through the Order List option.',
        'imagenes' => [
            'gracias' => 'compra-ingles.png'
        ],
        'subject' => 'Thank you for choosing us'

    ],
    'confirmarPass' => [
        'linea1' => 'We are pleased to inform you that your password change has been successfully completed!',
        'linea2' => 'To view or update additional information, please <a style="max-width: 350px; width: 100%; font-family: Helvetica, Arial, sans-serif; font-size:11px; line-height:16px; text-align:left; color:#8e8063!important" href="'.env('APP_URL').'/en'.'">log in</a> to your account.',
        'linea3' => 'If you did not request this password change, please disregard this email.',
        'linea4' => 'Thank you for trusting<br>Magia de Uco!',
        'imagenes' => [
            'recupera' => 'recup-contrasena-eng.jpg'
        ],
        'subject' => 'Password changed successfully'
    ]
];
