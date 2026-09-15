<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">{{ $thread->title }}</h2></x-slot>
    <div class="max-w-3xl mx-auto py-8 px-4 space-y-4">
        <a href="{{ route('forum.category', $thread->category) }}" class="text-sm text-emerald-700">← {{ $thread->category->name }}</a>
        <div class="bg-white border rounded-xl p-5">
            <p class="text-xs text-slate-500 mb-2">{{ $thread->user->name }} · {{ $thread->created_at->format('d/m/Y H:i') }}</p>
            <div class="prose prose-sm max-w-none whitespace-pre-wrap">{{ $thread->body }}</div>
            @if(auth()->user()->isAdmin())
                <form method="POST" action="{{ route('forum.thread.destroy', $thread) }}" class="mt-4" onsubmit="return confirm('Supprimer ce sujet ?')">
                    @csrf @method('DELETE')
                    <button class="text-sm text-red-600">Supprimer le sujet</button>
                </form>
            @endif
        </div>

        <h3 class="font-semibold">Réponses ({{ $thread->posts->count() }})</h3>
        @foreach($thread->posts as $post)
            <div class="bg-white border rounded-xl p-4">
                <p class="text-xs text-slate-500 mb-1">{{ $post->user->name }} · {{ $post->created_at->format('d/m/Y H:i') }}</p>
                <div class="whitespace-pre-wrap text-sm">{{ $post->body }}</div>
                @if(auth()->user()->isAdmin())
                    <form method="POST" action="{{ route('forum.post.destroy', $post) }}" class="mt-2" onsubmit="return confirm('Supprimer ?')">
                        @csrf @method('DELETE')
                        <button class="text-xs text-red-600">Supprimer</button>
                    </form>
                @endif
            </div>
        @endforeach

        @unless($thread->is_locked && !auth()->user()->isAdmin())
            <form method="POST" action="{{ route('forum.post.store', $thread) }}" class="bg-white border rounded-xl p-5 space-y-3">
                @csrf
                <x-input-label value="Votre réponse" />
                <textarea name="body" rows="4" class="w-full rounded-md border-stone-300" required>{{ old('body') }}</textarea>
                <x-primary-button>Répondre</x-primary-button>
            </form>
        @endunless
    </div>
</x-app-layout>
