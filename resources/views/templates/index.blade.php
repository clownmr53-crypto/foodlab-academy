<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Bibliothèque de templates</h2></x-slot>
    <div class="max-w-3xl mx-auto py-8 px-4 space-y-3">
        <p class="text-sm text-slate-600 mb-4">Fiches techniques, grilles de coûts et modèles prêts à l’emploi.</p>
        @forelse($templates as $template)
            <div class="bg-white border rounded-xl p-5 flex justify-between gap-4 items-start">
                <div>
                    <h3 class="font-semibold">{{ $template->title }}</h3>
                    @if($template->description)
                        <p class="text-sm text-slate-600 mt-1">{{ $template->description }}</p>
                    @endif
                </div>
                @if($template->hasDownload())
                    <a href="{{ route('templates.download', $template) }}" class="shrink-0 rounded-lg bg-emerald-600 text-white px-3 py-2 text-sm">Télécharger</a>
                @endif
            </div>
        @empty
            <p class="text-slate-500 text-sm">Aucun template publié.</p>
        @endforelse
    </div>
</x-app-layout>
