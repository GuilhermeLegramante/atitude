<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Certificado - {{ $module }}</title>
    <style>
        /* Configurações para o DomPDF renderizar corretamente em Paisagem */
        @page {
            margin: 0;
            /* Remove as margens da página para podermos controlar via body */
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            /* Helvetica costuma renderizar melhor no dompdf */
            margin: 0;
            padding: 50px;
            text-align: center;
            color: #333;
            border: 15px solid #115293;
            /* Adiciona uma borda elegante nas cores da escola */
            height: 100%;
        }

        .header {
            margin-bottom: 30px;
        }

        .logo {
            width: 180px;
            /* Um pouco maior para destaque */
        }

        .titulo {
            font-size: 50px;
            font-weight: bold;
            color: #115293;
            text-transform: uppercase;
            margin-top: 20px;
            letter-spacing: 5px;
        }

        .subtitulo {
            font-size: 24px;
            margin-top: 10px;
            margin-bottom: 40px;
            font-style: italic;
        }

        .nome-aluno {
            font-size: 42px;
            font-weight: bold;
            margin-bottom: 30px;
            color: #000;
            border-bottom: 2px solid #eee;
            display: inline-block;
            padding-bottom: 10px;
        }

        .texto-certificado {
            font-size: 22px;
            line-height: 1.8;
            margin: 0 50px 60px 50px;
        }

        .assinatura-box {
            position: absolute;
            bottom: 80px;
            width: 100%;
            left: 0;
        }

        .linha-assinatura {
            border-top: 1px solid #000;
            width: 400px;
            margin: 0 auto;
            padding-top: 10px;
            font-size: 16px;
            line-height: 1.4;
        }

        .data-emissao {
            position: absolute;
            bottom: 40px;
            right: 60px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>

<body>

    <div class="header">
        {{-- Verifique se o caminho da imagem está correto no seu /public --}}
        <img src="{{ public_path('img/atitude_logo_contorno.png') }}" alt="Atitude Idiomas" class="logo">
    </div>

    <div class="titulo">CERTIFICADO</div>
    <div class="subtitulo">Certificamos que</div>

    <div class="nome-aluno">{{ mb_strtoupper($student->name) }}</div>

    <div class="texto-certificado">
        concluiu com êxito o <strong>{{ $module }}</strong> do curso de
        <strong>{{ $course }}</strong>, ministrado pela
        <strong>Escola Atitude Idiomas</strong>, cumprindo todos os requisitos
        acadêmicos e a carga horária estabelecida para este nível.
    </div>

    <div class="assinatura-box">
        <div class="linha-assinatura">
            <strong>Eduardo Silveira Batista</strong><br>
            Diretor - Escola Atitude Idiomas<br>
            <small>CNPJ: 44.698.899/0001-33</small>
        </div>
    </div>

    <div class="data-emissao">
        Emitido em: {{ date('d/m/Y') }}
    </div>

</body>

</html>
