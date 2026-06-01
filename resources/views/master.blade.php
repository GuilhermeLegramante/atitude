<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Atitude Idiomas')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x" defer></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=Playfair+Display:ital,wght@1,600&display=swap"
        rel="stylesheet">
    <link rel="icon" href="https://ead.atitudeidiomas.com/img/icone.png">

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#2b2c43">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="/img/icone.png">

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('Service Worker registrado com sucesso!', reg.scope))
                    .catch(err => console.log('Falha ao registrar o Service Worker:', err));
            });
        }
    </script>

    <style>
        body {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial
        }

        .font-serifa {
            font-family: 'Playfair Display', serif;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-[#020916] text-gray-100 flex flex-col min-h-screen">

    @include('partials.navbar')

    <div class="pt-20"> {{-- Espaçamento para não cobrir o conteúdo devido à navbar fixa --}}
        @yield('content')
    </div>

    @include('partials.whatsapp')

    @include('partials.footer')

</body>

</html>
