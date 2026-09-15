<div x-data="{ show: !localStorage.getItem('fl_cookies') }" x-show="show" x-cloak
     class="fixed bottom-0 inset-x-0 z-50 p-4">
    <div class="mx-auto max-w-3xl rounded-xl bg-slate-900 text-white shadow-xl p-4 flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
        <p class="text-sm">
            Nous utilisons des cookies techniques pour le fonctionnement du site (session, CSRF, préférences).
            Voir la <a href="{{ route('legal.show', 'confidentialite') }}" class="underline">politique de confidentialité</a>.
        </p>
        <button type="button" class="rounded-lg bg-emerald-500 px-4 py-2 text-sm font-semibold text-slate-900"
                @click="localStorage.setItem('fl_cookies','1'); show=false">J'accepte</button>
    </div>
</div>
