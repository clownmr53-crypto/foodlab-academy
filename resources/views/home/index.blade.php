<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FoodLab Academy — Lancez votre produit alimentaire rentable en 30 jours</title>
    <meta name="description" content="Apprenez à créer, produire et vendre votre propre produit alimentaire grâce à une méthode pratique conçue pour les entrepreneurs africains.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important}</style>
    @include('components.analytics-snippets')
</head>
<body class="font-sans antialiased bg-stone-50 text-slate-800">
@include('components.analytics-body')
@include('layouts.navigation')

{{-- Hero --}}
<section class="bg-gradient-to-br from-emerald-700 to-teal-800 text-white">
    <div class="max-w-7xl mx-auto px-4 py-16 md:py-20 grid md:grid-cols-2 gap-10 items-center">
        <div>
            <h1 class="text-3xl md:text-5xl font-bold leading-tight mb-4">Lancez votre premier produit alimentaire rentable en 30 jours</h1>
            <p class="text-emerald-50 text-lg mb-8">Apprenez à créer, produire et vendre votre propre produit alimentaire grâce à une méthode pratique conçue pour les entrepreneurs africains.</p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('register') }}" class="rounded-xl bg-white text-emerald-800 px-5 py-3 font-semibold">Commencer gratuitement</a>
                <a href="#plans" class="rounded-xl border border-white/40 px-5 py-3 font-semibold">Voir les offres</a>
            </div>
        </div>
        <div class="bg-white/10 backdrop-blur rounded-2xl p-6 border border-white/20">
            <h2 class="font-semibold text-xl mb-4">Ce que vous obtenez</h2>
            <ul class="space-y-3 text-emerald-50">
                <li>✓ Méthode en 4 étapes pour lancer en 30 jours</li>
                <li>✓ 6 modules Premium avec livrables concrets</li>
                <li>✓ Calculateur de coût de revient (PDF & Excel)</li>
                <li>✓ Certificat officiel + QR (parcours Premium)</li>
            </ul>
        </div>
    </div>
</section>

{{-- Pourquoi 80% échouent --}}
<section class="max-w-7xl mx-auto px-4 py-16">
    <h2 class="text-2xl md:text-3xl font-bold text-center mb-3">Pourquoi 80% des projets alimentaires échouent en moins d'un an.</h2>
    <p class="text-center text-slate-600 mb-10 max-w-2xl mx-auto">Les erreurs classiques coûtent cher. FoodLab les anticipe avec vous.</p>
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-5">
        @foreach([
            ['Mauvais calcul des coûts', 'Vous vendez à perte sans le savoir. Chaque vente creuse votre trésorerie.'],
            ['Recettes non maîtrisées', 'Goût et qualité varient d\'un lot à l\'autre. Impossible de fidéliser.'],
            ['Absence de méthode', 'Vous avancez à tâtons, sans cap clair, en perdant temps et argent.'],
            ['Manque d\'accompagnement', 'Personne pour vous guider quand ça bloque. Vous abandonnez avant de réussir.'],
        ] as [$title, $desc])
            <div class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
                <h3 class="font-semibold text-lg mb-2">{{ $title }}</h3>
                <p class="text-sm text-slate-600">{{ $desc }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- Méthode 4 étapes --}}
<section class="bg-white border-y border-stone-200 py-16">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-2xl md:text-3xl font-bold text-center mb-3">FoodLab Academy vous accompagne, étape par étape.</h2>
        <p class="text-center text-slate-600 mb-10 max-w-2xl mx-auto">Une méthode unique conçue avec des entrepreneurs agroalimentaires africains qui ont réussi.</p>
        <div class="grid md:grid-cols-4 gap-5">
            @foreach([
                ['1', 'Concevoir votre produit', 'Recette, format, positionnement. Tout est cadré dès le départ.'],
                ['2', 'Calculer vos coûts', 'Ingrédients, emballage, main-d\'œuvre, transport. Zéro approximation.'],
                ['3', 'Fixer vos prix', 'Marge optimale validée par le marché. Vous vendez avec confiance.'],
                ['4', 'Préparer le lancement', 'Plan de mise sur le marché clair et actionnable. Prêt à distribuer.'],
            ] as [$n, $title, $desc])
                <div class="rounded-2xl bg-stone-50 border border-stone-100 p-5">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-emerald-600 text-white text-sm font-bold mb-3">{{ $n }}</span>
                    <h3 class="font-semibold mb-2">{{ $title }}</h3>
                    <p class="text-sm text-slate-600">{{ $desc }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Ce que vous repartez avec --}}
<section class="max-w-7xl mx-auto px-4 py-16">
    <h2 class="text-2xl md:text-3xl font-bold text-center mb-10">Ce que vous repartez avec, concrètement.</h2>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach([
            ['Un produit défini', 'Recette, format, cible — tout est clair et documenté.'],
            ['Un coût de revient calculé', 'Vous savez exactement combien votre produit vous coûte à produire.'],
            ['Un prix de vente validé', 'Marge optimale, compétitive sur votre marché local.'],
            ['Un plan de lancement prêt', 'Distribution, communication, premiers clients — tout planifié.'],
            ['Formations vidéo HD', 'Cours filmés en studio par des praticiens du secteur agroalimentaire africain.'],
            ['Calculateur de coût de revient', 'Outil interactif pour piloter vos marges avec exportation PDF et Excel.'],
            ['Modèles téléchargeables', 'Templates Excel, business plan, étiquettes produit prêts à utiliser.'],
            ['Accompagnement collectif', 'Sessions live mensuelles questions-réponses avec des experts du secteur.'],
            ['Communauté privée', 'Échangez avec d\'autres entrepreneurs du continent.'],
            ['Certification de fin', 'Validez vos acquis avec un certificat officiel FoodLab Academy.'],
        ] as $i => [$title, $desc])
            @if($i < 6)
            <div class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
                <h3 class="font-semibold mb-2">{{ $title }}</h3>
                <p class="text-sm text-slate-600">{{ $desc }}</p>
            </div>
            @endif
        @endforeach
    </div>
    <p class="text-center text-sm text-slate-500 mt-6">+ Templates, sessions Q&R, communauté privée et certification (selon l'offre).</p>
</section>

{{-- 6 modules Premium --}}
<section class="bg-emerald-50/60 border-y border-emerald-100 py-16">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-2xl md:text-3xl font-bold text-center mb-3">Les 6 modules du parcours Premium.</h2>
        <p class="text-center text-slate-600 mb-10">Chaque module se termine par un livrable concret et une évaluation pour valider vos acquis.</p>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($modules as $module)
                <div class="rounded-2xl bg-white border border-stone-200 p-5 shadow-sm">
                    <p class="text-xs font-semibold text-emerald-700 mb-1">Module {{ str_pad((string) $module->order, 2, '0', STR_PAD_LEFT) }}
                        @if($module->is_premium_only)<span class="ml-1 text-amber-700">Premium</span>@endif
                    </p>
                    <h3 class="font-semibold text-lg mb-2">{{ $module->title }}</h3>
                    <p class="text-sm text-slate-600">{{ $module->description }}</p>
                </div>
            @empty
                @foreach([
                    ['01', 'Conception du produit alimentaire', 'Développement de recette, choix du format et packaging, positionnement marché.'],
                    ['02', 'Calcul des coûts de revient', 'Ingrédients, emballage, main-d\'œuvre, transport et logistique.'],
                    ['03', 'Fixation des prix et stratégie de marge', 'Analyse concurrence, calcul de marge optimale, stratégies de pricing.'],
                    ['04', 'Réglementation et normes alimentaires', 'Normes sanitaires ANADA/FDA, procédures de certification, étiquetage.'],
                    ['05', 'Stratégie de lancement et distribution', 'Canaux de distribution, logistique de livraison, gestion des stocks.'],
                    ['06', 'Marketing et vente pour produits alimentaires', 'Branding, marketing digital, techniques de vente B2B et B2C.'],
                ] as [$n, $title, $desc])
                    <div class="rounded-2xl bg-white border border-stone-200 p-5 shadow-sm">
                        <p class="text-xs font-semibold text-emerald-700 mb-1">Module {{ $n }}</p>
                        <h3 class="font-semibold text-lg mb-2">{{ $title }}</h3>
                        <p class="text-sm text-slate-600">{{ $desc }}</p>
                    </div>
                @endforeach
            @endforelse
        </div>
    </div>
</section>

{{-- Pricing teaser --}}
<section id="plans" class="max-w-7xl mx-auto px-4 py-16">
    <h2 class="text-2xl md:text-3xl font-bold text-center mb-3">Choisissez votre offre.</h2>
    <p class="text-center text-slate-600 mb-10">Commencez gratuitement ou accédez au programme complet. Garantie {{ $guaranteeDays }} jours satisfait ou remboursé.</p>
    <div class="grid md:grid-cols-2 gap-6 max-w-4xl mx-auto">
        @foreach($plans as $key => $plan)
            @php
                $label = $plan['currency_label'] ?? 'FCFA';
                $isFree = (int) $plan['price'] <= 0;
            @endphp
            <div class="rounded-2xl border bg-white p-6 shadow-sm {{ $key === 'premium' ? 'border-emerald-500 ring-2 ring-emerald-100 relative' : 'border-stone-200' }}">
                @if($key === 'premium')
                    <span class="absolute -top-3 left-1/2 -translate-x-1/2 text-xs font-semibold bg-emerald-600 text-white px-3 py-1 rounded-full">⭐ Le plus populaire</span>
                @endif
                <p class="text-xs uppercase tracking-wide text-slate-500 mb-1">{{ $isFree ? 'Gratuit' : 'Payant' }}</p>
                <h3 class="text-xl font-bold">{{ $plan['name'] }}</h3>
                <p class="text-3xl font-bold text-emerald-700 my-3">
                    @if($isFree)
                        0 {{ $label }} <span class="text-base font-medium text-slate-500">/ pour toujours</span>
                    @else
                        {{ number_format($plan['price'], 0, ',', ' ') }} <span class="text-base font-medium text-slate-500">{{ $label }}</span>
                    @endif
                </p>
                <ul class="space-y-2 text-sm mb-6">
                    @foreach(($plan['features'] ?? []) as $feature)
                        <li class="flex gap-2 {{ ($feature['included'] ?? true) ? 'text-slate-700' : 'text-slate-400' }}">
                            <span>{{ ($feature['included'] ?? true) ? '✓' : '✗' }}</span>
                            <span>{{ $feature['text'] }}</span>
                        </li>
                    @endforeach
                </ul>
                @auth
                    <a href="{{ route('payments.plans') }}" class="inline-block w-full text-center rounded-lg {{ $key === 'premium' ? 'bg-emerald-600 text-white' : 'border border-emerald-600 text-emerald-700' }} px-4 py-2.5 text-sm font-semibold">
                        {{ $isFree ? 'Activer Starter' : 'Passer en Premium' }}
                    </a>
                @else
                    <a href="{{ route('register') }}" class="inline-block w-full text-center rounded-lg {{ $key === 'premium' ? 'bg-emerald-600 text-white' : 'border border-emerald-600 text-emerald-700' }} px-4 py-2.5 text-sm font-semibold">
                        {{ $isFree ? 'Commencer gratuitement' : 'Rejoindre Premium' }}
                    </a>
                @endauth
            </div>
        @endforeach
    </div>
    <p class="text-center text-xs text-slate-500 mt-6">Moyens de paiement Premium : {{ implode(' · ', $paymentMethods) }}</p>
</section>

{{-- FAQ accordion --}}
<section class="max-w-3xl mx-auto px-4 py-16">
    <h2 class="text-2xl md:text-3xl font-bold mb-3 text-center">Questions fréquentes.</h2>
    <p class="text-center text-slate-600 mb-8 text-sm">Une question qui n'apparaît pas ? <a href="{{ route('contact.create') }}" class="text-emerald-700 font-medium">Écrivez-nous</a>, on répond sous 24h.</p>
    <div class="space-y-3" x-data="{ open: null }">
        @foreach($faqs as $i => $faq)
            <div class="rounded-xl border border-stone-200 bg-white overflow-hidden">
                <button type="button" class="w-full text-left px-4 py-3 font-semibold flex justify-between items-center gap-3" @click="open = open === {{ $i }} ? null : {{ $i }}">
                    <span>{{ $faq->question }}</span>
                    <span class="text-emerald-700 text-lg" x-text="open === {{ $i }} ? '−' : '+'"></span>
                </button>
                <div class="px-4 pb-4 text-slate-600 text-sm" x-show="open === {{ $i }}" x-cloak>
                    {{ $faq->answer }}
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- TasteBox teaser --}}
<section class="max-w-5xl mx-auto px-4 py-12">
    <div class="rounded-2xl bg-stone-900 text-white p-8 md:p-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-amber-400 mb-2">Produit digital</p>
            <h2 class="text-2xl font-bold mb-2">TasteBox — kit filière à 15 000 FCFA</h2>
            <p class="text-stone-300 text-sm max-w-xl">4 filières (sauces, marinades, confitures, farines), 7 documents, templates et aperçu du jeu de cartes. Garantie 7 jours (distincte de la garantie Academy 14 jours).</p>
        </div>
        <a href="{{ route('tastebox') }}" class="inline-block rounded-xl bg-amber-500 hover:bg-amber-400 text-stone-900 px-5 py-3 font-semibold whitespace-nowrap">Découvrir TasteBox</a>
    </div>
</section>

{{-- Final CTA --}}
<section class="bg-gradient-to-br from-emerald-700 to-teal-800 text-white">
    <div class="max-w-3xl mx-auto px-4 py-16 text-center">
        <h2 class="text-2xl md:text-3xl font-bold mb-4">Votre produit alimentaire mérite d'exister.</h2>
        <p class="text-emerald-50 mb-8">Rejoignez plus de 1 200 entrepreneurs qui ont choisi une méthode claire pour lancer rentable.</p>
        <a href="{{ route('register') }}" class="inline-block rounded-xl bg-white text-emerald-800 px-6 py-3 font-semibold">Créer mon compte gratuit</a>
    </div>
</section>

@include('layouts.footer')
<x-cookie-banner />
@include('components.whatsapp-float')
</body>
</html>
