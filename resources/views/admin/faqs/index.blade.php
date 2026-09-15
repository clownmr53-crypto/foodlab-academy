<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">FAQ</h2></x-slot>
    <div class="max-w-5xl mx-auto py-8 px-4 space-y-4">
        @include('admin._nav')
        <a href="{{ route('admin.faqs.create') }}" class="inline-block rounded-lg bg-emerald-600 text-white px-3 py-2 text-sm">Nouvelle FAQ</a>
        <div class="bg-white border rounded-xl divide-y">
            @foreach($faqs as $faq)
                <div class="p-4 flex justify-between gap-3">
                    <p class="font-medium">{{ $faq->question }}</p>
                    <div class="flex gap-2 text-sm">
                        <a href="{{ route('admin.faqs.edit', $faq) }}">Modifier</a>
                        <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}">@csrf @method('DELETE')<button class="text-red-600">Suppr.</button></form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
