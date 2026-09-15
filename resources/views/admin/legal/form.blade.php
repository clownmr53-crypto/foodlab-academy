<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">{{ $page->title }}</h2></x-slot>
    <div class="max-w-3xl mx-auto py-8 px-4">
        <form method="POST" action="{{ route('admin.legal.update', $page) }}" class="bg-white border rounded-xl p-6 space-y-4">
            @csrf @method('PUT')
            <div><x-input-label value="Titre" /><x-text-input name="title" class="w-full mt-1" :value="old('title', $page->title)" required /></div>
            <div><x-input-label value="Contenu HTML" /><textarea name="content" rows="12" class="w-full mt-1 rounded-md border-stone-300" required>{{ old('content', $page->content) }}</textarea></div>
            <x-primary-button>Enregistrer</x-primary-button>
        </form>
    </div>
</x-app-layout>
