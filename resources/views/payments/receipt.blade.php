<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Recibo de Pagamento #{{ $payment->id }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            color: #333;
            margin: 40px;
        }

        .header {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }

        .logo {
            width: 140px;
            margin-bottom: 10px;
        }

        .company-info h2 {
            margin: 0;
            font-size: 20px;
            color: #115293;
        }

        .company-info h2 {
            margin: 0;
            font-size: 20px;
            color: #115293;
        }

        h1 {
            text-align: center;
            color: #444;
            margin-bottom: 30px;
        }

        .details {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 20px;
            background: #f9f9f9;
        }

        .details p {
            margin: 6px 0;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-top: 60px;
        }

        .label {
            font-weight: bold;
            color: #555;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .row .col {
            width: 48%;
        }

        .amount {
            font-size: 16px;
            font-weight: bold;
            color: #2e7d32;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ public_path('img/atitude_logo_contorno.png') }}" alt="Atitude Idiomas" class="logo">
        <div class="company-info">
            <h2>Atitude Idiomas</h2>
            <p>Plataforma EAD - www.atitudeidiomas.com</p>
        </div>
    </div>


    <h1>Recibo de Pagamento</h1>

    <div class="details">
        <div class="row">
            <div class="col">
                <p><span class="label">Aluno:</span> {{ $payment->student->name }}</p>
                <p><span class="label">Descrição:</span> {{ $payment->description }}</p>
                <p><span class="label">Status:</span> {{ ucfirst($payment->status) }}</p>
            </div>
            <div class="col">
                <p><span class="label">Data de Vencimento:</span> {{ $payment->due_date->format('d/m/Y') }}</p>
                <p><span class="label">Data de Pagamento:</span>
                    {{ $payment->payment_date ? $payment->payment_date->format('d/m/Y') : '-' }}</p>
                <p><span class="label">Forma de Pagamento:</span> {{ $payment->payment_method ?? '-' }}</p>
            </div>
        </div>

        <p class="amount">Valor pago: R$ {{ number_format($payment->amount, 2, ',', '.') }}</p>
    </div>

    <div class="footer">
        <p>Recibo gerado automaticamente pelo sistema.</p>
        <p>Obrigado pela preferência!</p>
    </div>

</body>

</html>
