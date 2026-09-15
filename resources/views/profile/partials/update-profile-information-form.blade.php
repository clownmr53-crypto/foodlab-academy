<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Informations du profil</h2>
        <p class="mt-1 text-sm text-gray-600">Mettez à jour votre nom, e-mail, pays, secteur et niveau.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">@csrf</form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" value="Nom" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="E-mail" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        Votre adresse e-mail n'est pas vérifiée.
                        <button form="send-verification" class="underline text-sm text-gray-600">Renvoyer l'e-mail de vérification</button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">Un nouveau lien a été envoyé.</p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="country" value="Pays" />
            <x-text-input id="country" name="country" type="text" class="mt-1 block w-full" :value="old('country', $user->country)" />
        </div>
        <div>
            <x-input-label for="sector" value="Secteur" />
            <x-text-input id="sector" name="sector" type="text" class="mt-1 block w-full" :value="old('sector', $user->sector)" />
        </div>
        <div>
            <x-input-label for="level" value="Niveau" />
            <select id="level" name="level" class="mt-1 block w-full rounded-md border-gray-300">
                @foreach(['débutant','intermédiaire','avancé'] as $l)
                    <option value="{{ $l }}" @selected(old('level', $user->level) === $l)>{{ ucfirst($l) }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Enregistrer</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">Enregistré.</p>
            @endif
        </div>
    </form>
</section>
