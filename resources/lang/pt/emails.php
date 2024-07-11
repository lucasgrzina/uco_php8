<?php
return [
    'general' => [
        'desuscribirme' => 'Quiero desuscribirme',
        'perfil' => 'Atualizar meu perfil',
        'tyc' => 'Términos y condiciones'
    ],
    'registro' => [
        'texto' => 'Para ter acesso ao seu perfil, você poderá ingressar o seu nome de usuário <span style="color: #8E8063;">@USUARIO</span> e a sua senha.',
        'btn_texto' => 'ENTRE NO SITE',
        'imagenes' => [
            'te-damos-la-bienvenida' => 'header-bienvenida-port.jpg',
            'quedate-atento' => 'novedades-port.jpg',
        ],
        'subject' => 'Bem-vindos',
        'url' => env('APP_URL').'/pt'
    ],
    'recuperar' => [
        'linea1' => 'Recebemos uma solicitação para restabelecer a senha da sua conta. Se for este o caso, ingresse novamente usando esta senha por única vez.',
        'linea2' => 'Ao fazer isso, não se esqueça de modificá-la por uma nova e pessoal.',
        'linea3' => 'Se você não solicitou o restabelecimento da senha, ignore este e-mail',
        'btn_texto' => 'RESTABLECER CONTRASE&Ntilde;A',
        'imagenes' => [
            'recupera' => 'contrasena-port.jpg'
        ],
        'subject' => 'Magia de Uco - Nova Senha'
    ],
    'pedido' => [
        'info' => 'Informações sobre a sua compra',
        'nro' => 'O seu número de pedido é ',
        'producto' => 'Produto',
        'fecha' => 'Data',
        'hora' => 'Hora',
        'subtotal' => 'Subtotal',
        'envio' => 'Custo de envio',
        'total' => 'Total',
        'infoEnvio' => 'Informações sobre o seu envio',
        'conocer' => 'Para saber o estado do seu pedido, utilize a opção “Lista de Pedidos” para ingressar na <a style="color:#000!important;" href="_lnk_cuenta_">sua conta</a> no nosso website.',
        'imagenes' => [
            'gracias' => 'compra-portugues.png'
        ],
        'subject' => 'Obrigado por nos escolher'
    ],
    'confirmarPass' => [
        'linea1' => 'Temos o prazer de informar que a alteração da sua senha foi realizada com sucesso!',
        'linea2' => 'Para visualizar ou atualizar informações adicionais, por favor, <a style="max-width: 350px; width: 100%; font-family: Helvetica, Arial, sans-serif; font-size:11px; line-height:16px; text-align:left; color:#CDCFAD!important" href="'.env('APP_URL').'/en'.'">faça login</a> na sua conta.',
        'linea3' => 'Se você não solicitou essa alteração de senha, por favor, desconsidere este e-mail.',
        'linea4' => 'Obrigado por confiar na<br>Magia de Uco!',
        'imagenes' => [
            'recupera' => 'recup-contrasena-eng.jpg'
        ],
        'subject' => 'Senha alterada com sucesso'
    ]
];
