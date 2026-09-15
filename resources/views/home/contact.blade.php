<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Contact</h2></x-slot>
    <div class="max-w-xl mx-auto py-10 px-4">
        <form method="POST" action="{{ route('contact.store') }}" class="bg-white border rounded-xl p-6 space-y-4">
            @csrf
            <div>
                <x-input-label for="name" value="Nom" />
                <x-text-input id="name" name="name" class="block mt-1 w-full" :value="old('name', auth()->user()->name ?? '')" required />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="email" value="E-mail" />
                <x-text-input id="email" type="email" name="email" class="block mt-1 w-full" :value="old('email', auth()->user()->email ?? '')" required />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="subject" value="Sujet" />
                <x-text-input id="subject" name="subject" class="block mt-1 w-full" :value="old('subject')" />
            </div>
            <div>
                <x-input-label for="message" value="Message" />
                <textarea id="message" name="message" rows="5" class="block mt-1 w-full rounded-md border-stone-300" required>{{ old('message') }}</textarea>
                <x-input-error :messages="$errors->get('message')" class="mt-1" />
            </div>
            <x-primary-button>Envoyer</x-primary-button>
        </form>
    </div>
</x-app-layout>
