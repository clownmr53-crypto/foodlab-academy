<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">{{ $lesson->exists ? 'Modifier' : 'Créer' }} une leçon</h2></x-slot>
    <div class="max-w-xl mx-auto py-8 px-4">
        <form method="POST" action="{{ $lesson->exists ? route('admin.modules.lessons.update', [$module, $lesson]) : route('admin.modules.lessons.store', $module) }}" class="bg-white border rounded-xl p-6 space-y-4">
            @csrf
            @if($lesson->exists) @method('PUT') @endif
            <div><x-input-label value="Titre" /><x-text-input name="title" class="w-full mt-1" :value="old('title', $lesson->title)" required /></div>
            <div><x-input-label value="Slug" /><x-text-input name="slug" class="w-full mt-1" :value="old('slug', $lesson->slug)" /></div>
            <div><x-input-label value="Contenu HTML" /><textarea name="content" rows="6" class="w-full mt-1 rounded-md border-stone-300">{{ old('content', $lesson->content) }}</textarea></div>
            <div><x-input-label value="Ordre" /><x-text-input type="number" name="order" class="w-full mt-1" :value="old('order', $lesson->order ?: 1)" required /></div>
            <div><x-input-label value="Provider vidéo" />
                <select name="video_provider" class="w-full mt-1 rounded-md border-stone-300">
                    <option value="bunny" @selected(old('video_provider', $lesson->video_provider) === 'bunny')>Bunny</option>
                    <option value="vimeo" @selected(old('video_provider', $lesson->video_provider) === 'vimeo')>Vimeo</option>
                </select>
            </div>
            <div><x-input-label value="Video ID" /><x-text-input name="video_id" class="w-full mt-1" :value="old('video_id', $lesson->video_id)" /></div>
            <div><x-input-label value="Durée (min)" /><x-text-input type="number" name="duration" class="w-full mt-1" :value="old('duration', $lesson->duration)" /></div>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_premium_only" value="1" @checked(old('is_premium_only', $lesson->is_premium_only))> Premium</label>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $lesson->exists ? $lesson->is_published : true))> Publié</label>
            <x-primary-button>Enregistrer</x-primary-button>
        </form>
    </div>
</x-app-layout>
