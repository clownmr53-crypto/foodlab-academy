<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Message</h2></x-slot>
    <div class="max-w-2xl mx-auto py-8 px-4">
        <div class="bg-white border rounded-xl p-6 space-y-3">
            <p><strong>De :</strong> {{ $message->name }} &lt;{{ $message->email }}&gt;</p>
            <p><strong>Sujet :</strong> {{ $message->subject ?: '—' }}</p>
            <p class="whitespace-pre-wrap">{{ $message->message }}</p>
            <form method="POST" action="{{ route('admin.contact.destroy', $message) }}">@csrf @method('DELETE')
                <button class="text-red-600 text-sm">Supprimer</button>
            </form>
        </div>
    </div>
</x-app-layout>
