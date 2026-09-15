<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-3">
            <h2 class="font-semibold text-xl">{{ $category->name }}</h2>
            <a href="{{ route('forum.thread.create', $category) }}" class="rounded-lg bg-emerald-600 text-white px-3 py-2 text-sm">Nouveau sujet</a>
        </div>
    </x-slot>
    <div class="max-w-4xl mx-auto py-8 px-4 space-y-3">
        <a href="{{ route('forum.index') }}" class="text-sm text-emerald-700">← Forum</a>
        @forelse($threads as $thread)
            <a href="{{ route('forum.thread', $thread) }}" class="block bg-white border rounded-xl px-4 py-3 hover:border-emerald-400">
                <p class="font-medium">{{ $thread->title }}</p>
                <p class="text-xs text-slate-500">par {{ $thread->user->name }} · {{ $thread->created_at->format('d/m/Y H:i') }} · {{ $thread->posts_count }} réponse(s)</p>
            </a>
        @empty
            <p class="text-slate-500 text-sm">Aucun sujet. Soyez le premier à en créer un.</p>
        @endforelse
        <div>{{ $threads->links() }}</div>
    </div>
</x-app-layout>
