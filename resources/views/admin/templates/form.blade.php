<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Template</h2></x-slot>
    <div class="max-w-xl mx-auto py-8 px-4">
        <form method="POST" enctype="multipart/form-data" action="{{ $template->exists ? route('admin.templates.update', $template) : route('admin.templates.store') }}" class="bg-white border rounded-xl p-6 space-y-4">
            @csrf @if($template->exists) @method('PUT') @endif
            <div><x-input-label value="Titre" /><x-text-input name="title" class="w-full mt-1" :value="old('title', $template->title)" required /></div>
            <div><x-input-label value="Description" /><textarea name="description" rows="3" class="w-full mt-1 rounded-md border-stone-300">{{ old('description', $template->description) }}</textarea></div>
            <div><x-input-label value="URL externe (optionnel)" /><x-text-input name="external_url" class="w-full mt-1" :value="old('external_url', $template->external_url)" /></div>
            <div>
                <x-input-label value="Fichier (max 10 Mo)" />
                <input type="file" name="file" class="mt-1 text-sm" />
                @if($template->file_path)<p class="text-xs text-slate-500 mt-1">Actuel : {{ $template->file_path }}</p>@endif
            </div>
            <div><x-input-label value="Ordre" /><x-text-input type="number" name="order" class="w-full mt-1" :value="old('order', $template->order ?: 0)" /></div>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $template->exists ? $template->is_published : true))> Publié</label>
            <x-primary-button>Enregistrer</x-primary-button>
        </form>
    </div>
</x-app-layout>
