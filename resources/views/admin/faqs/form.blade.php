<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">FAQ</h2></x-slot>
    <div class="max-w-xl mx-auto py-8 px-4">
        <form method="POST" action="{{ $faq->exists ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}" class="bg-white border rounded-xl p-6 space-y-4">
            @csrf @if($faq->exists) @method('PUT') @endif
            <div><x-input-label value="Question" /><x-text-input name="question" class="w-full mt-1" :value="old('question', $faq->question)" required /></div>
            <div><x-input-label value="Réponse" /><textarea name="answer" rows="5" class="w-full mt-1 rounded-md border-stone-300" required>{{ old('answer', $faq->answer) }}</textarea></div>
            <div><x-input-label value="Ordre" /><x-text-input type="number" name="order" class="w-full mt-1" :value="old('order', $faq->order ?: 0)" /></div>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $faq->exists ? $faq->is_published : true))> Publié</label>
            <x-primary-button>Enregistrer</x-primary-button>
        </form>
    </div>
</x-app-layout>
