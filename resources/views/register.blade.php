@extends('master')

@section('content')
    @include('partials.hero')

    <section class="max-w-7xl mx-auto px-4 py-12">
        <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
            <h3 class="text-2xl font-bold text-[#2b2c43]">Cadastre-se</h3>
        </div>

        <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-md p-6 sm:p-8 text-[#2b2c43] space-y-5">
            <p class="text-lg font-semibold flex items-center gap-2">
                🎉 Seja muito bem-vindo(a)!
            </p>

            <p class="leading-relaxed">
                Estamos muito felizes em ter você conosco.
                Ao clicar no botão abaixo, você será direcionado para nosso
                <strong>ambiente seguro de pagamento</strong>, onde poderá informar apenas
                os dados necessários para concluir sua matrícula —
                sem burocracia e sem duplicidade de informações.
            </p>

            <p class="leading-relaxed">
                <strong>Após a confirmação do pagamento</strong>, nossa equipe da
                <strong>Atitude Idiomas</strong> entrará em contato para enviar
                <strong>os dados de acesso à plataforma</strong> e orientar você
                sobre os próximos passos da sua jornada no aprendizado.
            </p>

            <div class="flex flex-col gap-3 pt-4">
                <a href="https://api.ipag.com.br/subscriptions?id=0da474fc8e382f9c6d6d774bb433339a44dfad978047df66550b6951703ff332712f4b70"
                    target="_blank"
                    class="inline-flex items-center justify-center rounded-xl bg-[#2b2c43] px-8 py-3 text-white font-semibold hover:opacity-90 transition">
                    👉 Ir para pagamento
                </a>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-sm text-gray-600">
                    <span class="flex items-center gap-1">
                        🔒 Pagamento 100% seguro
                    </span>
                    <span class="flex items-center gap-1">
                        ⏱️ Leva menos de 2 minutos
                    </span>
                    <span class="flex items-center gap-1">
                        📱 Contato via WhatsApp após a confirmação
                    </span>
                </div>
            </div>
        </div>
    </section>


    @include('partials.tools')
@endsection
