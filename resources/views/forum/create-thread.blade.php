<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Nouveau sujet — {{ $category->name }}</h2></x-slot>
    <div class="max-w-xl mx-auto py-8 px-4">
        <form method="POST" action="{{ route('forum.thread.store', $category) }}" class="bg-white border rounded-xl p-6 space-y-4">
            @csrf
            <div>
                <x-input-label value="Titre" />
                <x-text-input name="title" class="w-full mt-1" :value="old('title')" required />
                <x-input-error :messages="$errors->get('title')" class="mt-1" />
            </div>
            <div>
                <x-input-label value="Message" />
                <textarea name="body" rows="6" class="w-full mt-1 rounded-md border-stone-300" required>{{ old('body') }}</textarea>
                <x-input-error :messages="$errors->get('body')" class="mt-1" />
            </div>
            <x-primary-button>Publier</x-primary-button>
        </form>
    </div>
</x-app-layout>
