<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Forum communauté</h2></x-slot>
    <div class="max-w-4xl mx-auto py-8 px-4 space-y-4">
        <p class="text-slate-600 text-sm">Échangez avec les autres apprenants FoodLab. La publication nécessite un compte authentifié avec plan.</p>
        @forelse($categories as $category)
            <a href="{{ route('forum.category', $category) }}" class="block bg-white border rounded-xl p-5 hover:border-emerald-400">
                <div class="flex justify-between gap-3">
                    <div>
                        <h3 class="font-semibold text-lg">{{ $category->name }}</h3>
                        @if($category->description)
                            <p class="text-sm text-slate-600 mt-1">{{ $category->description }}</p>
                        @endif
                    </div>
                    <span class="text-sm text-slate-500 whitespace-nowrap">{{ $category->threads_count }} sujet(s)</span>
                </div>
            </a>
        @empty
            <div class="bg-white border rounded-xl p-6 text-slate-500">Aucune catégorie pour le moment.</div>
        @endforelse
    </div>
</x-app-layout>
