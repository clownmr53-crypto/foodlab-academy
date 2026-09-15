<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div>
            <x-input-label for="name" value="Nom" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="email" value="E-mail" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="country" value="Pays" />
            <x-text-input id="country" class="block mt-1 w-full" type="text" name="country" :value="old('country')" />
        </div>
        <div class="mt-4">
            <x-input-label for="sector" value="Secteur" />
            <select id="sector" name="sector" class="block mt-1 w-full rounded-md border-gray-300">
                <option value="">—</option>
                @foreach(['Restauration','Traiteur','Boulangerie','Pâtisserie','Street food','Production agroalimentaire','Autre'] as $s)
                    <option value="{{ $s }}" @selected(old('sector') === $s)>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <div class="mt-4">
            <x-input-label for="level" value="Niveau" />
            <select id="level" name="level" class="block mt-1 w-full rounded-md border-gray-300">
                <option value="">—</option>
                @foreach(['débutant','intermédiaire','avancé'] as $l)
                    <option value="{{ $l }}" @selected(old('level') === $l)>{{ ucfirst($l) }}</option>
                @endforeach
            </select>
        </div>
        <div class="mt-4">
            <x-input-label for="password" value="Mot de passe" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirmer le mot de passe" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
        </div>
        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">Déjà inscrit ?</a>
            <x-primary-button class="ms-4">Créer mon compte</x-primary-button>
        </div>
    </form>
</x-guest-layout>
