<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ public_path('img/background_certificado.jpg') }}');
            background-size: cover;
            background-repeat: no-repeat;
            color: white;
            text-align: center;
        }

        /* Título "CERTIFICADO" já está na imagem, se precisar sobrepor use esta classe */
        .titulo {
            position: absolute;
            top: 120px;
            width: 100%;
            font-size: 60px;
            letter-spacing: 8px;
            font-weight: bold;
            display: none;
            /* Esconda se a imagem já tiver o texto */
        }

        /* Nome do Aluno - Posicionado acima da primeira linha branca */
        .nome-aluno {
            position: absolute;
            top: 300px;
            /* Ajuste conforme a posição da linha na imagem */
            width: 100%;
            font-size: 45px;
            font-weight: bold;
            color: #ffffff;
        }

        /* Texto de conclusão - Abaixo do nome */
        .texto-conclusao {
            position: absolute;
            top: 460px;
            left: 15%;
            width: 70%;
            font-size: 20px;
            line-height: 1.5;
            color: #e0e0e0;
        }

        /* Rodapé com assinatura e CNPJ */
        /* Container do rodapé */
        .footer-info {
            position: absolute;
            bottom: 190px;
            /* Ajuste para subir ou descer o bloco todo */
            width: 100%;
            text-align: center;
        }

        /* Estilização da Assinatura Digital */
        .assinatura-img {
            width: 250px;
            /* Tamanho proporcional à linha */
            height: auto;
            margin-bottom: -15px;
            /* Puxa a assinatura para cima da linha branca */
            opacity: 0.9;
            /* Deixa levemente suave no fundo escuro */
        }

        .dados-empresa {
            font-size: 14px;
            color: #ffffff;
            line-height: 1.6;
            text-transform: uppercase;
            border-top: 1px solid rgba(255, 255, 255, 0.3);
            /* Linha sutil acima do nome */
            display: inline-block;
            padding-top: 10px;
            width: 400px;
        }

        /* Container das Assinaturas */
        .assinaturas-container {
            position: absolute;
            bottom: 120px;
            /* Ajuste para alinhar com as linhas da imagem */
            width: 100%;
            text-align: center;
        }

        .assinatura-box {
            display: inline-block;
            width: 40%;
            /* Divide o espaço entre as duas assinaturas */
            vertical-align: top;
            margin: 0 20px;
        }

        .assinatura-img {
            height: 70px;
            /* Altura fixa para manter proporção */
            width: auto;
            margin-bottom: -10px;
            opacity: 0.9;
        }

        .dados-assinatura {
            font-size: 11px;
            /* Fonte menor para caber no layout */
            color: #ffffff;
            line-height: 1.4;
            text-transform: uppercase;
            border-top: 1px solid rgba(255, 255, 255, 0.3);
            padding-top: 5px;
        }
    </style>
</head>

<body>

    <div class="nome-aluno">
        {{ mb_strtoupper($student->name) }}
    </div>

    <div class="texto-conclusao">
        Concluiu com êxito o <strong>{{ $module }}</strong> do curso de
        <strong>{{ $course }}</strong> da Escola Atitude Idiomas,
        com carga horária de {{ $hours }} horas.
    </div>

    <div class="assinaturas-container">
        <div class="assinatura-box">
            <img src="{{ public_path('img/assinatura_diretor.png') }}" class="assinatura-img">
            <div class="dados-assinatura">
                <strong>EDUARDO SILVEIRA BATISTA</strong><br>
                DIRETOR - ESCOLA ATITUDE IDIOMAS<br>
                CNPJ: 44.698.899/0001-33
            </div>
        </div>

        <div class="assinatura-box">
            <img src="{{ public_path('img/assinatura_coord.png') }}" class="assinatura-img">
            <div class="dados-assinatura">
                <strong>CAROLINA TONELOTTO LORENZONI</strong><br>
                COORDENADORA<br>
                ESCOLA ATITUDE IDIOMAS
            </div>
        </div>
    </div>

</body>

</html>
