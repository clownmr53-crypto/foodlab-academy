<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Contact — FoodLab Academy</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('components.analytics-snippets')
</head>
<body class="font-sans antialiased bg-stone-50 text-slate-800">
@include('components.analytics-body')
@include('layouts.navigation')

<div class="max-w-xl mx-auto py-12 px-4">
    <h1 class="text-2xl font-bold mb-2">Contact</h1>
    <p class="text-sm text-slate-600 mb-6">Une question sur TasteBox, la formation ou un partenariat ? Écrivez-nous — réponse sous 24h en jours ouvrés.</p>

    @if(session('status'))
        <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3">{{ session('status') }}</div>
    @endif

    @if($whatsappUrl)
        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="mb-6 inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-4 py-2 text-sm font-semibold">WhatsApp rapide</a>
    @else
        <p class="mb-6 text-xs text-amber-800 bg-amber-50 border border-amber-100 rounded-lg px-3 py-2">WhatsApp non configuré (définissez <code>WHATSAPP_NUMBER</code> ou <code>WHATSAPP_URL</code>).</p>
    @endif

    <form method="POST" action="{{ route('contact.store') }}" class="bg-white border rounded-xl p-6 space-y-4">
        @csrf
        <div>
            <x-input-label for="name" value="Nom" />
            <x-text-input id="name" name="name" class="block mt-1 w-full" :value="old('name', auth()->user()->name ?? '')" required />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>
        <div>
            <x-input-label for="email" value="E-mail" />
            <x-text-input id="email" type="email" name="email" class="block mt-1 w-full" :value="old('email', auth()->user()->email ?? '')" required />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>
        <div>
            <x-input-label for="whatsapp" value="WhatsApp (optionnel)" />
            <x-text-input id="whatsapp" name="whatsapp" class="block mt-1 w-full" :value="old('whatsapp')" placeholder="+229 …" />
            <x-input-error :messages="$errors->get('whatsapp')" class="mt-1" />
        </div>
        <div>
            <x-input-label for="country" value="Pays" />
            <select id="country" name="country" class="mt-1 w-full rounded-md border-stone-300">
                <option value="">— Choisir —</option>
                @foreach(['Bénin','Côte d\'Ivoire','Sénégal','Cameroun','Togo','Burkina Faso','Autre'] as $c)
                    <option value="{{ $c }}" @selected(old('country') === $c)>{{ $c }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('country')" class="mt-1" />
        </div>
        <div>
            <x-input-label for="subject_preset" value="Sujet" />
            @php $preset = old('subject_preset', request('sujet')); @endphp
            <select id="subject_preset" name="subject_preset" class="mt-1 w-full rounded-md border-stone-300">
                <option value="">— Choisir —</option>
                @foreach($subjectPresets as $key => $label)
                    <option value="{{ $key }}" @selected($preset === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <x-input-label for="subject" value="Sujet libre (optionnel)" />
            <x-text-input id="subject" name="subject" class="block mt-1 w-full" :value="old('subject')" />
        </div>
        <div>
            <x-input-label for="message" value="Message" />
            <textarea id="message" name="message" rows="5" class="block mt-1 w-full rounded-md border-stone-300" required>{{ old('message') }}</textarea>
            <x-input-error :messages="$errors->get('message')" class="mt-1" />
        </div>
        <x-primary-button>Envoyer</x-primary-button>
    </form>
</div>

@include('layouts.footer')
<x-cookie-banner />
@include('components.whatsapp-float')
</body>
</html>
