<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Mon certificat</h2></x-slot>
    <div class="max-w-lg mx-auto py-10 px-4">
        @if($certificate)
            <div class="bg-white border rounded-xl p-6 space-y-3">
                <p class="font-semibold text-lg">Certificat délivré</p>
                <p>Code : <code class="bg-stone-100 px-2 py-1 rounded">{{ $certificate->code }}</code></p>
                <p class="text-sm text-slate-600">Émis le {{ $certificate->issued_at->format('d/m/Y') }}</p>
                <a href="{{ route('certificates.download') }}" class="inline-block rounded-lg bg-emerald-600 text-white px-4 py-2 text-sm">Télécharger le PDF</a>
                <p class="text-xs text-slate-500">Vérification publique : {{ route('certificates.verify', ['code' => $certificate->code]) }}</p>
            </div>
        @else
            <div class="bg-white border rounded-xl p-6">
                <p>Aucun certificat pour le moment. Terminez toutes les leçons du parcours Premium.</p>
            </div>
        @endif
    </div>
</x-app-layout>
