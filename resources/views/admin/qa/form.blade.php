<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Session Q&R</h2></x-slot>
    <div class="max-w-xl mx-auto py-8 px-4">
        <form method="POST" action="{{ $session->exists ? route('admin.qa.update', $session) : route('admin.qa.store') }}" class="bg-white border rounded-xl p-6 space-y-4">
            @csrf @if($session->exists) @method('PUT') @endif
            <div><x-input-label value="Titre" /><x-text-input name="title" class="w-full mt-1" :value="old('title', $session->title)" required /></div>
            <div><x-input-label value="Description" /><textarea name="description" rows="3" class="w-full mt-1 rounded-md border-stone-300">{{ old('description', $session->description) }}</textarea></div>
            <div><x-input-label value="Date & heure" /><x-text-input type="datetime-local" name="session_at" class="w-full mt-1" :value="old('session_at', optional($session->session_at)->format('Y-m-d\TH:i'))" required /></div>
            <div><x-input-label value="Lien visio" /><x-text-input name="visio_link" class="w-full mt-1" :value="old('visio_link', $session->visio_link)" /></div>
            <div><x-input-label value="URL replay" /><x-text-input name="replay_url" class="w-full mt-1" :value="old('replay_url', $session->replay_url)" /></div>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $session->exists ? $session->is_published : true))> Publié</label>
            <x-primary-button>Enregistrer</x-primary-button>
        </form>
    </div>
</x-app-layout>
