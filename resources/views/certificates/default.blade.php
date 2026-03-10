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
        .footer-info {
            position: absolute;
            bottom: 80px;
            width: 100%;
            font-size: 14px;
            line-height: 1.6;
            text-transform: uppercase;
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
        com carga horária de 50 horas.
    </div>

    {{-- <div class="footer-info">
        EDUARDO SILVEIRA BATISTA<br>
        ESCOLA ATITUDE IDIOMAS<br>
        CNPJ: 44.698.899/0001-33
    </div> --}}

</body>

</html>
