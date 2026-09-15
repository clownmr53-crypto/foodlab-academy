<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Messages de contact</h2></x-slot>
    <div class="max-w-5xl mx-auto py-8 px-4 space-y-4">
        @include('admin._nav')
        <div class="bg-white border rounded-xl divide-y">
            @foreach($messages as $message)
                <a href="{{ route('admin.contact.show', $message) }}" class="block p-4 hover:bg-stone-50 {{ $message->read_at ? '' : 'bg-emerald-50/40' }}">
                    <p class="font-medium">{{ $message->name }} — {{ $message->subject ?: 'Sans sujet' }}</p>
                    <p class="text-xs text-slate-500">{{ $message->created_at->format('d/m/Y H:i') }}</p>
                </a>
            @endforeach
        </div>
        {{ $messages->links() }}
    </div>
</x-app-layout>
