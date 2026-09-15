<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Témoignage</h2></x-slot>
    <div class="max-w-xl mx-auto py-8 px-4">
        <form method="POST" action="{{ $testimonial->exists ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}" class="bg-white border rounded-xl p-6 space-y-4">
            @csrf @if($testimonial->exists) @method('PUT') @endif
            <div><x-input-label value="Nom" /><x-text-input name="author_name" class="w-full mt-1" :value="old('author_name', $testimonial->author_name)" required /></div>
            <div><x-input-label value="Rôle" /><x-text-input name="author_role" class="w-full mt-1" :value="old('author_role', $testimonial->author_role)" /></div>
            <div><x-input-label value="Pays" /><x-text-input name="country" class="w-full mt-1" :value="old('country', $testimonial->country)" /></div>
            <div><x-input-label value="Contenu" /><textarea name="content" rows="4" class="w-full mt-1 rounded-md border-stone-300" required>{{ old('content', $testimonial->content) }}</textarea></div>
            <div><x-input-label value="Note" /><x-text-input type="number" min="1" max="5" name="rating" class="w-full mt-1" :value="old('rating', $testimonial->rating ?: 5)" /></div>
            <div><x-input-label value="Ordre" /><x-text-input type="number" name="order" class="w-full mt-1" :value="old('order', $testimonial->order ?: 0)" /></div>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $testimonial->exists ? $testimonial->is_published : true))> Publié</label>
            <x-primary-button>Enregistrer</x-primary-button>
        </form>
    </div>
</x-app-layout>
