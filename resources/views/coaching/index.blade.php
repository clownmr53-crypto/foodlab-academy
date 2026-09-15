<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Réservation coaching</h2></x-slot>
    <div class="max-w-3xl mx-auto py-8 px-4 space-y-6">
        <p class="text-slate-600 text-sm">Réservez une session de coaching personnalisée pour votre food business.</p>

        @if(filled($calendlyUrl))
            <div class="bg-white border rounded-xl p-2 overflow-hidden">
                <iframe src="{{ $calendlyUrl }}" width="100%" height="700" frameborder="0" title="Calendly" class="rounded-lg"></iframe>
            </div>
            <p class="text-xs text-slate-500">Si le widget ne s’affiche pas, <a class="text-emerald-700 underline" href="{{ $calendlyUrl }}" target="_blank" rel="noopener">ouvrez Calendly</a>.</p>
        @else
            <form method="POST" action="{{ route('coaching.store') }}" class="bg-white border rounded-xl p-6 space-y-4">
                @csrf
                <p class="text-sm text-amber-800 bg-amber-50 rounded-lg px-3 py-2">Calendly n’est pas configuré (CALENDLY_URL). Envoyez une demande ci-dessous.</p>
                <div>
                    <x-input-label value="Nom" />
                    <x-text-input name="name" class="w-full mt-1" :value="old('name', $user->name)" required />
                </div>
                <div>
                    <x-input-label value="E-mail" />
                    <x-text-input type="email" name="email" class="w-full mt-1" :value="old('email', $user->email)" required />
                </div>
                <div>
                    <x-input-label value="Téléphone" />
                    <x-text-input name="phone" class="w-full mt-1" :value="old('phone')" />
                </div>
                <div>
                    <x-input-label value="Créneau souhaité" />
                    <x-text-input name="preferred_slot" class="w-full mt-1" :value="old('preferred_slot')" placeholder="Ex. mardi matin" />
                </div>
                <div>
                    <x-input-label value="Message" />
                    <textarea name="message" rows="4" class="w-full mt-1 rounded-md border-stone-300">{{ old('message') }}</textarea>
                </div>
                <x-primary-button>Envoyer la demande</x-primary-button>
            </form>
        @endif
    </div>
</x-app-layout>
