<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Mes certificats</h2></x-slot>
    <div class="max-w-lg mx-auto py-10 px-4 space-y-4">
        @forelse($certificates as $certificate)
            <div class="bg-white border rounded-xl p-6 space-y-3">
                <p class="font-semibold text-lg">{{ $certificate->label() }}</p>
                <p>Code : <code class="bg-stone-100 px-2 py-1 rounded">{{ $certificate->code }}</code></p>
                <p class="text-sm text-slate-600">Émis le {{ $certificate->issued_at->format('d/m/Y') }}</p>
                <a href="{{ route('certificates.download', ['type' => $certificate->type]) }}" class="inline-block rounded-lg bg-emerald-600 text-white px-4 py-2 text-sm">Télécharger le PDF</a>
                <p class="text-xs text-slate-500">Vérification : {{ route('certificates.verify', ['code' => $certificate->code]) }}</p>
            </div>
        @empty
            <div class="bg-white border rounded-xl p-6 space-y-2">
                <p>Aucun certificat pour le moment.</p>
                <ul class="text-sm text-slate-600 list-disc pl-5">
                    <li><strong>Starter</strong> : terminez les modules 1 à 3 → certificat de participation.</li>
                    <li><strong>Premium</strong> : terminez les 6 modules → certification Premium + QR.</li>
                </ul>
            </div>
        @endforelse
    </div>
</x-app-layout>
