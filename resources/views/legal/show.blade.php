<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">{{ $page->title }}</h2></x-slot>
    <div class="max-w-3xl mx-auto py-10 px-4">
        <article class="prose prose-stone bg-white border rounded-xl p-8 max-w-none">
            {!! $page->content !!}
        </article>
    </div>
</x-app-layout>
