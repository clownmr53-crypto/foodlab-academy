<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Catégories forum</h2></x-slot>
    <div class="max-w-3xl mx-auto py-8 px-4 space-y-4">
        @include('admin._nav')
        <form method="POST" action="{{ route('admin.forum.categories.store') }}" class="bg-white border rounded-xl p-5 space-y-3">
            @csrf
            <div><x-input-label value="Nom" /><x-text-input name="name" class="w-full mt-1" required /></div>
            <div><x-input-label value="Description" /><textarea name="description" rows="2" class="w-full mt-1 rounded-md border-stone-300"></textarea></div>
            <div><x-input-label value="Ordre" /><x-text-input type="number" name="order" class="w-full mt-1" value="0" /></div>
            <x-primary-button>Ajouter</x-primary-button>
        </form>
        <div class="bg-white border rounded-xl divide-y">
            @foreach($categories as $category)
                <div class="p-4 flex justify-between gap-3">
                    <div>
                        <p class="font-medium">{{ $category->name }}</p>
                        <p class="text-xs text-slate-500">{{ $category->threads_count }} sujet(s)</p>
                    </div>
                    <form method="POST" action="{{ route('admin.forum.categories.destroy', $category) }}">@csrf @method('DELETE')<button class="text-sm text-red-600">Suppr.</button></form>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
