<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FoodLab Academy — Formation food business</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important}</style>
    @include('components.analytics-snippets')
</head>
<body class="font-sans antialiased bg-stone-50 text-slate-800">
@include('components.analytics-body')
@include('layouts.navigation')
<section class="bg-gradient-to-br from-emerald-700 to-teal-800 text-white">
    <div class="max-w-7xl mx-auto px-4 py-20 grid md:grid-cols-2 gap-10 items-center">
        <div>
            <p class="uppercase tracking-wide text-emerald-200 text-sm mb-2">LMS pour métiers de bouche</p>
            <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-4">Maîtrisez votre coût de revient et vos marges</h1>
            <p class="text-emerald-50 text-lg mb-8">6 modules pratiques, calculateur pro, paiements Mobile Money & carte, certificat Premium vérifiable.</p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('register') }}" class="rounded-xl bg-white text-emerald-800 px-5 py-3 font-semibold">Commencer</a>
                <a href="#plans" class="rounded-xl border border-white/40 px-5 py-3 font-semibold">Voir les plans</a>
            </div>
        </div>
        <div class="bg-white/10 backdrop-blur rounded-2xl p-6 border border-white/20">
            <h2 class="font-semibold text-xl mb-4">Ce que vous obtenez</h2>
            <ul class="space-y-3 text-emerald-50">
                <li>✓ Modules 1–3 (Starter) + 4–6 (Premium)</li>
                <li>✓ Calculateur coût de revient (PDF & Excel)</li>
                <li>✓ Vidéos sécurisées (Bunny / Vimeo)</li>
                <li>✓ Certificat PDF + QR (parcours Premium)</li>
            </ul>
        </div>
    </div>
</section>

<section id="plans" class="max-w-7xl mx-auto px-4 py-16">
    <h2 class="text-2xl font-bold mb-8 text-center">Nos formules</h2>
    <div class="grid md:grid-cols-2 gap-6 max-w-4xl mx-auto">
        @foreach($plans as $key => $plan)
            <div class="rounded-2xl border bg-white p-6 shadow-sm {{ $key === 'premium' ? 'border-emerald-500 ring-2 ring-emerald-100' : 'border-stone-200' }}">
                <h3 class="text-xl font-bold">{{ $plan['name'] }}</h3>
                <p class="text-3xl font-bold text-emerald-700 my-3">{{ number_format($plan['price'], 0, ',', ' ') }} <span class="text-base font-medium text-slate-500">{{ $plan['currency'] }}</span></p>
                <p class="text-sm text-slate-600 mb-4">Modules {{ implode(', ', $plan['modules_access']) }}</p>
                <a href="{{ route('register') }}" class="inline-block rounded-lg bg-emerald-600 text-white px-4 py-2 text-sm font-semibold">S'inscrire</a>
            </div>
        @endforeach
    </div>
</section>

<section class="bg-white border-y border-stone-200 py-16">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-2xl font-bold mb-8 text-center">Ils en parlent</h2>
        <div class="grid md:grid-cols-3 gap-6">
            @foreach($testimonials as $t)
                <blockquote class="rounded-xl bg-stone-50 p-5 border border-stone-100">
                    <p class="text-slate-700 mb-3">“{{ $t->content }}”</p>
                    <footer class="text-sm font-semibold">{{ $t->author_name }} — {{ $t->author_role }} ({{ $t->country }})</footer>
                </blockquote>
            @endforeach
        </div>
    </div>
</section>

<section class="max-w-3xl mx-auto px-4 py-16">
    <h2 class="text-2xl font-bold mb-8 text-center">FAQ</h2>
    <div class="space-y-4">
        @foreach($faqs as $faq)
            <details class="rounded-xl border border-stone-200 bg-white p-4">
                <summary class="font-semibold cursor-pointer">{{ $faq->question }}</summary>
                <p class="mt-2 text-slate-600 text-sm">{{ $faq->answer }}</p>
            </details>
        @endforeach
    </div>
</section>
@include('layouts.footer')
<x-cookie-banner />
@include('components.whatsapp-float')
</body>
</html>
