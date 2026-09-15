<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">{{ $module->exists ? 'Modifier' : 'Créer' }} un module</h2></x-slot>
    <div class="max-w-xl mx-auto py-8 px-4">
        @include('admin._nav')
        <form method="POST" action="{{ $module->exists ? route('admin.modules.update', $module) : route('admin.modules.store') }}" class="bg-white border rounded-xl p-6 space-y-4">
            @csrf
            @if($module->exists) @method('PUT') @endif
            <div><x-input-label value="Titre" /><x-text-input name="title" class="w-full mt-1" :value="old('title', $module->title)" required /></div>
            <div><x-input-label value="Slug" /><x-text-input name="slug" class="w-full mt-1" :value="old('slug', $module->slug)" /></div>
            <div><x-input-label value="Description" /><textarea name="description" class="w-full mt-1 rounded-md border-stone-300" rows="3">{{ old('description', $module->description) }}</textarea></div>
            <div><x-input-label value="Livrable" /><x-text-input name="deliverable" class="w-full mt-1" :value="old('deliverable', $module->deliverable)" placeholder="Ex. Fiche produit complète" /></div>
            <div><x-input-label value="Ordre" /><x-text-input type="number" name="order" class="w-full mt-1" :value="old('order', $module->order ?: 1)" required /></div>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_premium_only" value="1" @checked(old('is_premium_only', $module->is_premium_only))> Premium uniquement</label>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $module->exists ? $module->is_published : true))> Publié</label>
            <x-primary-button>Enregistrer</x-primary-button>
        </form>
    </div>
</x-app-layout>
