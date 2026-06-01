<?php

return [
    'name' => 'Atitude Idiomas',
    'short_name' => 'Atitude',
    'start_url' => '/',
    'background_color' => '#020916', // Cor do fundo da tela de splash
    'theme_color' => '#2b2c43',      // Cor da barra do navegador/sistema
    'display' => 'standalone',       // Faz parecer um app nativo, sem barras do navegador
    'orientation' => 'any',
    'status_bar' => 'black-translucent',
    'icons' => [
        '72x72' => [
            'path' => '/img/icone.png', // Substitua pelos caminhos dos seus ícones reais se preferir
            'purpose' => 'any'
        ],
        '152x152' => [
            'path' => '/img/icone.png',
            'purpose' => 'any'
        ],
        '512x512' => [
            'path' => '/img/icone.png',
            'purpose' => 'any'
        ],
        // Adicione outros tamanhos se o pacote exigir
    ],
    'custom' => []
];
