<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Vérifier un certificat</h2></x-slot>
    <div class="max-w-lg mx-auto py-10 px-4 space-y-6">
        <form method="GET" class="bg-white border rounded-xl p-6 flex gap-2">
            <x-text-input name="code" class="w-full" :value="$code" placeholder="CODE-CERTIFICAT" />
            <x-primary-button>Vérifier</x-primary-button>
        </form>
        @if($code !== '')
            @if($certificate)
                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-6">
                    <p class="font-bold text-emerald-800">Certificat authentique</p>
                    <p>Titulaire : {{ $certificate->user->name }}</p>
                    <p>Code : {{ $certificate->code }}</p>
                    <p>Émis le : {{ $certificate->issued_at->format('d/m/Y') }}</p>
                </div>
            @else
                <div class="bg-red-50 border border-red-200 rounded-xl p-6 text-red-800">Aucun certificat trouvé pour ce code.</div>
            @endif
        @endif
    </div>
</x-app-layout>
