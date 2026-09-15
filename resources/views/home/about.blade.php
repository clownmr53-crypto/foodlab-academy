<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>À propos — FoodLab Academy</title>
    <meta name="description" content="Mission, valeurs et équipe FoodLab Academy — outils pratiques pour entrepreneurs agroalimentaires africains, depuis Cotonou.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('components.analytics-snippets')
</head>
<body class="font-sans antialiased bg-stone-50 text-slate-800">
@include('components.analytics-body')
@include('layouts.navigation')

<section class="bg-gradient-to-br from-emerald-50 to-orange-50 border-b border-stone-200">
    <div class="max-w-4xl mx-auto px-4 py-16 text-center">
        <p class="text-xs font-bold uppercase tracking-widest text-emerald-700 mb-3">Notre histoire</p>
        <h1 class="text-3xl md:text-5xl font-bold mb-4">Nés du terrain, pour le terrain.</h1>
        <p class="text-lg text-slate-600 max-w-2xl mx-auto">FoodLab Academy est né à Cotonou d'un constat simple : le talent et les ressources sont là, mais la méthode manque. Nous comblons ce vide.</p>
    </div>
</section>

<section class="max-w-4xl mx-auto px-4 py-14 space-y-6">
    <h2 class="text-2xl font-bold">Notre mission</h2>
    <p class="text-slate-700">Transformer chaque idée culinaire en entreprise viable. En Afrique de l'Ouest, les marchés regorgent d'ingrédients locaux remarquables. Pourtant, trop de projets agroalimentaires s'arrêtent dans la première année faute d'accompagnement concret.</p>
    <p class="text-slate-700">Nous créons des outils pratiques, accessibles et ancrés dans le contexte local — Mobile Money, maquis, marchés hebdomadaires, normes sanitaires régionales.</p>
    <p class="font-semibold text-emerald-800">Notre conviction : une méthode claire + des ingrédients locaux + la passion du fondateur = une entreprise qui dure.</p>
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 pt-4 text-center">
        @foreach([['+1200','entrepreneurs'],['6','pays'],['4','filières kit'],['72','cartes pédagogiques'],['100%','ancrage local']] as [$n,$l])
            <div class="rounded-xl bg-white border border-stone-200 p-4">
                <p class="text-xl font-bold text-emerald-700">{{ $n }}</p>
                <p class="text-xs text-slate-500">{{ $l }}</p>
            </div>
        @endforeach
    </div>
</section>

<section class="bg-white border-y border-stone-200 py-14">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="text-2xl font-bold text-center mb-8">Nos valeurs</h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach([
                ['🌱', 'Ancrage local', 'Nos contenus parlent de marchés réels, de filières ouest-africaines et d\'outils utilisables à Cotonou comme à Abidjan.'],
                ['🎯', 'Praticité avant tout', 'Chaque ressource doit servir dès le jour J, avec le matériel d\'une cuisine artisanale standard.'],
                ['💡', 'Méthode éprouvée', 'Tester petit, apprendre vite, ajuster avec les retours clients — une approche Lean adaptée au terrain africain.'],
                ['🤝', 'Communauté d\'abord', 'Au-delà des documents, nous tissons un réseau d\'entrepreneurs qui s\'entraident.'],
            ] as [$icon, $t, $d])
                <div class="rounded-2xl border border-stone-200 bg-stone-50 p-5">
                    <p class="text-2xl mb-2">{{ $icon }}</p>
                    <h3 class="font-semibold mb-2">{{ $t }}</h3>
                    <p class="text-sm text-slate-600">{{ $d }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="max-w-4xl mx-auto px-4 py-14">
    <h2 class="text-2xl font-bold text-center mb-8">L'équipe</h2>
    <div class="grid md:grid-cols-2 gap-6">
        <div class="rounded-2xl bg-white border border-stone-200 p-6">
            <p class="text-3xl mb-3">👨🏾</p>
            <h3 class="font-bold text-lg">Lionel Hounsou</h3>
            <p class="text-sm text-emerald-700 mb-2">Fondateur & Directeur pédagogique</p>
            <p class="text-sm text-slate-600">Entrepreneur agroalimentaire, formateur et initiateur des parcours FoodLab / TasteBox. Basé à Cotonou, Bénin.</p>
        </div>
        <div class="rounded-2xl bg-white border border-stone-200 p-6">
            <p class="text-3xl mb-3">👩🏾</p>
            <h3 class="font-bold text-lg">L'équipe terrain</h3>
            <p class="text-sm text-emerald-700 mb-2">Formateurs & mentors</p>
            <p class="text-sm text-slate-600">Praticiens de la production, du packaging et de la vente locale, disponibles pour accompagner les membres via les canaux FoodLab.</p>
        </div>
    </div>
</section>

<section class="bg-gradient-to-br from-emerald-700 to-teal-800 text-white">
    <div class="max-w-3xl mx-auto px-4 py-14 text-center space-y-5">
        <h2 class="text-2xl md:text-3xl font-bold">Prêt à rejoindre l'aventure FoodLab ?</h2>
        <p class="text-emerald-50">Débutant ou déjà en activité : choisissez le kit TasteBox ou le parcours Academy.</p>
        <div class="flex flex-wrap justify-center gap-3">
            <a href="{{ route('tastebox') }}" class="rounded-xl bg-white text-emerald-800 px-5 py-3 font-semibold">Découvrir TasteBox</a>
            @if($whatsappUrl)
                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="rounded-xl border border-white/40 px-5 py-3 font-semibold">Nous écrire sur WhatsApp</a>
            @else
                <a href="{{ route('contact.create') }}" class="rounded-xl border border-white/40 px-5 py-3 font-semibold">Nous contacter</a>
            @endif
        </div>
    </div>
</section>

@include('layouts.footer')
<x-cookie-banner />
@include('components.whatsapp-float')
</body>
</html>
