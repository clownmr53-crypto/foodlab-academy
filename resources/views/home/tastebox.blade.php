<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TasteBox — Kit entrepreneurial agroalimentaire | FoodLab Academy</title>
    <meta name="description" content="TasteBox : kit PDF pour lancer sauces, marinades, confitures ou farines. Guides, templates et jeu de cartes pédagogique — {{ number_format($price, 0, ',', ' ') }} {{ $currencyLabel }}.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important}</style>
    @include('components.analytics-snippets')
</head>
<body class="font-sans antialiased bg-stone-50 text-slate-800" x-data="{ filiere: 'sauces', card: null }">
@include('components.analytics-body')
@include('layouts.navigation')

{{-- Hero --}}
<section class="bg-stone-900 text-white">
    <div class="max-w-4xl mx-auto px-4 py-16 md:py-20 text-center">
        <p class="inline-block text-xs font-bold uppercase tracking-widest text-amber-400 border border-amber-400/40 rounded-full px-3 py-1 mb-5">TasteBox par FoodLab Academy</p>
        <h1 class="text-3xl md:text-5xl font-black leading-tight mb-4">Goûte · Crée · Réussis<br><span class="text-amber-400">Votre premier produit alimentaire, en kit.</span></h1>
        <p class="text-stone-300 text-lg max-w-2xl mx-auto mb-8">Un dossier pratique pour démarrer une activité agroalimentaire en Afrique de l'Ouest : méthode, outils business et feuille de route — livré en PDF après paiement.</p>
        <div class="flex flex-wrap justify-center gap-3 mb-10">
            <a href="#commander" class="rounded-xl bg-amber-600 hover:bg-amber-500 px-6 py-3 font-semibold">Choisir ma TasteBox — {{ number_format($price, 0, ',', ' ') }} {{ $currencyLabel }}</a>
            <a href="#filieres" class="rounded-xl border border-white/30 px-6 py-3 font-semibold">Voir les 4 filières</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            @foreach([['7','documents par box'],['12','templates business'],['72','cartes pédagogiques'],['100%','ancrage local']] as [$n,$l])
                <div class="rounded-xl bg-white/5 border border-white/10 p-3">
                    <p class="text-xl font-bold text-amber-400">{{ $n }}</p>
                    <p class="text-stone-400 text-xs">{{ $l }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Comparatif --}}
<section class="max-w-5xl mx-auto px-4 py-14">
    <h2 class="text-2xl md:text-3xl font-bold text-center mb-3">Vous avez l'idée. Mais par où commencer ?</h2>
    <p class="text-center text-slate-600 mb-8 max-w-2xl mx-auto">Sans cadre clair, beaucoup abandonnent avant le premier lot. TasteBox regroupe le minimum vital pour passer à l'action.</p>
    <div class="grid md:grid-cols-3 gap-5">
        <div class="rounded-2xl border border-stone-200 bg-white p-5">
            <p class="font-semibold text-red-700 mb-2">Sans kit structuré</p>
            <p class="text-sm text-slate-600">Recettes éparpillées, pas de guide conservation, pricing au feeling.</p>
        </div>
        <div class="rounded-2xl border border-stone-200 bg-white p-5">
            <p class="font-semibold text-amber-700 mb-2">Sans méthode business</p>
            <p class="text-sm text-slate-600">Coût de revient flou, canaux de vente improvisés, trésorerie fragile.</p>
        </div>
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
            <p class="font-semibold text-emerald-800 mb-2">Avec TasteBox</p>
            <p class="text-sm text-slate-700">Itinéraire de l'ingrédient à la vente : docs, templates et jeu pour s'entraîner.</p>
        </div>
    </div>
</section>

{{-- Contenu 7 docs --}}
<section class="bg-white border-y border-stone-200 py-14">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="text-2xl font-bold text-center mb-2">Contenu de chaque box</h2>
        <p class="text-center text-slate-600 mb-8 text-sm">7 documents + 12 templates + jeu de cartes (aperçu gratuit ci-dessous). Livraison email / WhatsApp après paiement.</p>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach([
                ['01', 'Panorama de filière', 'Marché local, opportunités, risques et exemples inspirants.'],
                ['02', 'Manuel de démarrage', 'Bases techniques, hygiène, matériel minimal, enchaînement des étapes.'],
                ['03', 'Fiches pratiques', 'Ingrédients, emballages, étiquetage, conservation selon le produit.'],
                ['04', 'Recettes de référence', 'Deux formulations d\'étude avec dosages et variantes (contenu kit).'],
                ['05', 'Canevas business', 'Partenaires, coûts, revenus, segments — à personnaliser en 30 min.'],
                ['06', 'Pistes marketing', 'Nom, story, WhatsApp Business, dégustations, fidélisation.'],
                ['07', 'Feuille de route', 'Tester → Structurer → Développer → Industrialiser.'],
            ] as [$n, $t, $d])
                <div class="rounded-xl border border-stone-200 p-4">
                    <p class="text-xs font-bold text-amber-700 mb-1">{{ $n }}</p>
                    <h3 class="font-semibold mb-1">{{ $t }}</h3>
                    <p class="text-sm text-slate-600">{{ $d }}</p>
                </div>
            @endforeach
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 sm:col-span-2 lg:col-span-2">
                <h3 class="font-semibold mb-1">+ Boîte à outils (12 templates)</h3>
                <p class="text-sm text-slate-700">Canvas, pitch, persona, budget, tests Lean, suivi d'apprentissage… prêts à remplir.</p>
            </div>
        </div>
    </div>
</section>

{{-- 4 filières --}}
<section id="filieres" class="max-w-5xl mx-auto px-4 py-14">
    <h2 class="text-2xl font-bold text-center mb-3">4 filières, 4 marchés porteurs</h2>
    <p class="text-center text-slate-600 mb-6">Chaque kit est orienté filière — même prix, contenu adapté.</p>
    <div class="flex flex-wrap justify-center gap-2 mb-6">
        @foreach([
            'sauces' => '🌶️ Sauces',
            'marinades' => '🫙 Marinades',
            'confitures' => '🍯 Confitures',
            'farines' => '🌾 Farines',
        ] as $key => $label)
            <button type="button" @click="filiere='{{ $key }}'"
                    class="rounded-full px-4 py-2 text-sm font-semibold border"
                    :class="filiere==='{{ $key }}' ? 'bg-amber-600 text-white border-amber-600' : 'bg-white border-stone-200 text-slate-700'">
                {{ $label }}
            </button>
        @endforeach
    </div>

    <div class="rounded-2xl bg-white border border-stone-200 p-6" x-show="filiere==='sauces'">
        <h3 class="text-xl font-bold mb-2">Sauces pimentées</h3>
        <p class="text-sm text-slate-600 mb-4">Positionnez une sauce signature sur un marché artisanal encore peu structuré. Le kit couvre variétés locales, process de base, conservation et identité de marque.</p>
        <ul class="text-sm space-y-2 text-slate-700 mb-4">
            <li>✓ Lecture des profils aromatiques et usages culinaires</li>
            <li>✓ Trame de fabrication & mise en pot</li>
            <li>✓ Pistes de storytelling et canaux de proximité</li>
        </ul>
        <p class="text-2xl font-bold text-amber-700">{{ number_format($price, 0, ',', ' ') }} {{ $currencyLabel }} <span class="text-sm font-medium text-slate-500">/ box PDF</span></p>
    </div>
    <div class="rounded-2xl bg-white border border-stone-200 p-6" x-show="filiere==='marinades'" x-cloak>
        <h3 class="text-xl font-bold mb-2">Marinades</h3>
        <p class="text-sm text-slate-600 mb-4">Répondez à la demande des maquis, snacks et grillades avec des marinades stables et vendables. Focus B2B local et dosages simples.</p>
        <ul class="text-sm space-y-2 text-slate-700 mb-4">
            <li>✓ Familles de marinades et durées de conservation</li>
            <li>✓ Tableau d'ingrédients de marché</li>
            <li>✓ Approche restaurants / fast-foods de quartier</li>
        </ul>
        <p class="text-2xl font-bold text-amber-700">{{ number_format($price, 0, ',', ' ') }} {{ $currencyLabel }} <span class="text-sm font-medium text-slate-500">/ box PDF</span></p>
    </div>
    <div class="rounded-2xl bg-white border border-stone-200 p-6" x-show="filiere==='confitures'" x-cloak>
        <h3 class="text-xl font-bold mb-2">Confitures & tartines</h3>
        <p class="text-sm text-slate-600 mb-4">Valorisez fruits tropicaux et pertes post-récolte en produits premium pour boulangeries, hôtels et coffrets cadeaux.</p>
        <ul class="text-sm space-y-2 text-slate-700 mb-4">
            <li>✓ Bases de cuisson / gélification / mise en pot</li>
            <li>✓ Idées de formats événementiels</li>
            <li>✓ Ciblage B2B urbain</li>
        </ul>
        <p class="text-2xl font-bold text-amber-700">{{ number_format($price, 0, ',', ' ') }} {{ $currencyLabel }} <span class="text-sm font-medium text-slate-500">/ box PDF</span></p>
    </div>
    <div class="rounded-2xl bg-white border border-stone-200 p-6" x-show="filiere==='farines'" x-cloak>
        <h3 class="text-xl font-bold mb-2">Farines infantiles</h3>
        <p class="text-sm text-slate-600 mb-4">Filère à fort impact nutritionnel : formulation, itinéraire technique et partenariats (crèches, centres de santé, ONG).</p>
        <ul class="text-sm space-y-2 text-slate-700 mb-4">
            <li>✓ Rôles nutritionnels des céréales & légumineuses locales</li>
            <li>✓ Enchaînement transformation → emballage</li>
            <li>✓ Messages rassurants pour les parents</li>
        </ul>
        <p class="text-2xl font-bold text-amber-700">{{ number_format($price, 0, ',', ' ') }} {{ $currencyLabel }} <span class="text-sm font-medium text-slate-500">/ box PDF</span></p>
    </div>
</section>

{{-- Jeu de cartes teaser — original prompts, not proprietary card copy --}}
<section class="bg-stone-900 text-white py-14">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="text-2xl font-bold text-center mb-2">Jeu de cartes pédagogique — aperçu</h2>
        <p class="text-center text-stone-400 mb-8 text-sm max-w-2xl mx-auto">3 cartes démo gratuites. Le reste du jeu (69 cartes + règles) est inclus avec la TasteBox. Textes d'aperçu originaux FoodLab — pas de reproduction de contenus propriétaires.</p>
        <div class="grid md:grid-cols-3 gap-4 mb-6">
            @foreach([
                ['idea', '💡', 'Idée de départ', 'Quel problème client résolvez-vous ? En une phrase, quelle est votre promesse unique ?'],
                ['market', '🛒', 'Carte marché', 'Listez 3 concurrents de proximité et ce que les clients leur reprochent aujourd\'hui.'],
                ['client', '🤝', 'Premier client', 'Qui peut acheter votre premier lot cette semaine ? Où le rencontrer sans budget pub ?'],
            ] as [$id, $icon, $title, $teaser])
                <button type="button" @click="card='{{ $id }}'" class="text-left rounded-2xl border border-amber-500/40 bg-amber-500/10 p-5 hover:bg-amber-500/20">
                    <span class="text-xs font-bold text-amber-400">GRATUIT</span>
                    <p class="text-2xl my-2">{{ $icon }}</p>
                    <h3 class="font-semibold mb-1">{{ $title }}</h3>
                    <p class="text-sm text-stone-300">{{ $teaser }}</p>
                    <p class="text-xs text-amber-300 mt-3">Voir la carte →</p>
                </button>
            @endforeach
        </div>
        <div class="grid md:grid-cols-3 gap-4 opacity-70">
            @foreach([['💰','Fixer le prix','Calcul coût / marge / positionnement'],['📱','Vendre en messagerie','Séquence de messages pour convertir'],['⚖️','Cadre légal','Points de vigilance étiquetage & formalisation']] as [$i,$t,$d])
                <div class="rounded-2xl border border-white/10 bg-white/5 p-5 relative">
                    <span class="absolute top-3 right-3 text-xs bg-stone-700 px-2 py-0.5 rounded">🔒 BOX</span>
                    <p class="text-2xl mb-2">{{ $i }}</p>
                    <h3 class="font-semibold">{{ $t }}</h3>
                    <p class="text-sm text-stone-400">{{ $d }}</p>
                    <p class="text-xs text-stone-500 mt-3">Débloqué avec TasteBox</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Modal demo cards --}}
    <div x-show="card" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4" @keydown.escape.window="card=null">
        <div class="bg-white text-slate-800 rounded-2xl max-w-md w-full p-6 shadow-xl" @click.outside="card=null">
            <template x-if="card==='idea'">
                <div>
                    <p class="text-xs font-bold text-amber-700 mb-2">CARTE DÉMO · Idée</p>
                    <h3 class="text-xl font-bold mb-3">Clarifier votre promesse</h3>
                    <ol class="text-sm space-y-2 list-decimal list-inside text-slate-700">
                        <li>Quel désagrément le client ressent-il aujourd'hui ?</li>
                        <li>Quelle transformation concrète proposez-vous ?</li>
                        <li>Pourquoi vous (savoir-faire, accès matière, réseau) ?</li>
                    </ol>
                </div>
            </template>
            <template x-if="card==='market'">
                <div>
                    <p class="text-xs font-bold text-amber-700 mb-2">CARTE DÉMO · Marché</p>
                    <h3 class="text-xl font-bold mb-3">Cartographier la concurrence</h3>
                    <ol class="text-sm space-y-2 list-decimal list-inside text-slate-700">
                        <li>Où se vendent déjà des produits similaires près de chez vous ?</li>
                        <li>Quels prix constatés (bas / médian / haut) ?</li>
                        <li>Quelle faille (goût, hygiène, dispo, packaging) pouvez-vous combler ?</li>
                    </ol>
                </div>
            </template>
            <template x-if="card==='client'">
                <div>
                    <p class="text-xs font-bold text-amber-700 mb-2">CARTE DÉMO · Client</p>
                    <h3 class="text-xl font-bold mb-3">Trouver le premier acheteur</h3>
                    <ol class="text-sm space-y-2 list-decimal list-inside text-slate-700">
                        <li>Choisissez un lieu de test (marché, bureau, maquis).</li>
                        <li>Offrez une dégustation courte avec feedback écrit.</li>
                        <li>Proposez un mini-lot payant sous 48h.</li>
                    </ol>
                </div>
            </template>
            <button type="button" class="mt-6 w-full rounded-lg bg-stone-900 text-white py-2 font-semibold" @click="card=null">Fermer</button>
        </div>
    </div>
</section>

{{-- Process --}}
<section class="max-w-5xl mx-auto px-4 py-14">
    <h2 class="text-2xl font-bold text-center mb-8">De la commande au premier lot</h2>
    <div class="grid md:grid-cols-4 gap-4">
        @foreach([
            ['1','Vous commandez','Choisissez la filière, payez en Mobile Money ou carte.'],
            ['2','Vous recevez le PDF','Lien email + confirmation WhatsApp, accès immédiat.'],
            ['3','Vous préparez','Templates + feuille de route pour structurer le test.'],
            ['4','Vous produisez','Premier lot, feedback clients, itération.'],
        ] as [$n,$t,$d])
            <div class="rounded-xl bg-white border border-stone-200 p-4">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-amber-600 text-white text-sm font-bold mb-2">{{ $n }}</span>
                <h3 class="font-semibold mb-1">{{ $t }}</h3>
                <p class="text-sm text-slate-600">{{ $d }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- FAQ TasteBox --}}
<section class="bg-white border-y border-stone-200 py-14">
    <div class="max-w-3xl mx-auto px-4" x-data="{ open: null }">
        <h2 class="text-2xl font-bold text-center mb-8">FAQ TasteBox</h2>
        @foreach([
            ['C\'est quoi une TasteBox ?', 'Un kit PDF entrepreneurial pour une filière agroalimentaire : guides, trames business, templates et jeu de cartes pédagogique. Livré après paiement.'],
            ['Comment je reçois le contenu ?', 'Par email (lien de téléchargement) et confirmation WhatsApp, généralement en quelques minutes après validation du paiement.'],
            ['Faut-il de l\'expérience ?', 'Non. Le parcours est pensé pour débuter avec du matériel de cuisine courant et des étapes progressives.'],
            ['Quels paiements ?', implode(' · ', $paymentMethods)],
            ['Quelle garantie ?', 'Garantie satisfait ou remboursé de '.$guaranteeDays.' jours sur TasteBox (distincte de la garantie 14 jours du parcours Academy).'],
            ['Support après achat ?', 'Oui, via le formulaire contact ou WhatsApp si configuré — pour clarifier l\'usage des outils, pas pour remplacer une formation complète.'],
        ] as $i => [$q, $a])
            <div class="border border-stone-200 rounded-xl mb-3 overflow-hidden">
                <button type="button" class="w-full text-left px-4 py-3 font-semibold flex justify-between gap-3" @click="open = open === {{ $i }} ? null : {{ $i }}">
                    <span>{{ $q }}</span><span x-text="open === {{ $i }} ? '−' : '+'"></span>
                </button>
                <div class="px-4 pb-4 text-sm text-slate-600" x-show="open === {{ $i }}" x-cloak>{{ $a }}</div>
            </div>
        @endforeach
    </div>
</section>

{{-- CTA commande --}}
<section id="commander" class="bg-gradient-to-br from-amber-700 to-amber-900 text-white">
    <div class="max-w-3xl mx-auto px-4 py-14 text-center space-y-5">
        <h2 class="text-2xl md:text-3xl font-bold">Commander votre TasteBox</h2>
        <p class="text-amber-100">{{ number_format($price, 0, ',', ' ') }} {{ $currencyLabel }} · accès immédiat · garantie {{ $guaranteeDays }} jours</p>
        <p class="text-sm text-amber-50">Paiement : {{ implode(' · ', $paymentMethods) }}</p>
        <div class="flex flex-wrap justify-center gap-3">
            @if($whatsappUrl)
                <a href="{{ $whatsappUrl }}?text={{ urlencode('Bonjour, je souhaite commander une TasteBox (filière à préciser).') }}"
                   target="_blank" rel="noopener"
                   class="rounded-xl bg-white text-amber-900 px-6 py-3 font-semibold">Commander via WhatsApp</a>
            @else
                <a href="{{ route('contact.create', ['sujet' => 'tastebox']) }}" class="rounded-xl bg-white text-amber-900 px-6 py-3 font-semibold">Demander l'achat (contact)</a>
                <p class="w-full text-xs text-amber-100">Configurez WHATSAPP_NUMBER ou WHATSAPP_URL pour activer le bouton WhatsApp.</p>
            @endif
            <a href="{{ route('contact.create') }}" class="rounded-xl border border-white/40 px-6 py-3 font-semibold">Formulaire contact</a>
            <a href="{{ route('register') }}" class="rounded-xl border border-white/40 px-6 py-3 font-semibold">Créer un compte Academy</a>
        </div>
        <p class="text-xs text-amber-100/80">TasteBox = produit digital distinct du parcours Premium Academy (garantie 14 jours).</p>
    </div>
</section>

@include('layouts.footer')
<x-cookie-banner />
@include('components.whatsapp-float')
</body>
</html>
