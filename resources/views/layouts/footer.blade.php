<footer class="mt-16 border-t border-stone-200 bg-white">
    <div class="max-w-7xl mx-auto px-4 py-10 grid md:grid-cols-3 gap-8 text-sm text-slate-600">
        <div>
            <p class="font-semibold text-emerald-700 text-base mb-2">FoodLab Academy</p>
            <p>Lancez votre produit alimentaire rentable en 30 jours — méthode pour entrepreneurs africains.</p>
        </div>
        <div>
            <p class="font-semibold mb-2">Légal</p>
            <ul class="space-y-1">
                <li><a class="hover:text-emerald-700" href="{{ route('legal.show', 'cgv') }}">CGV</a></li>
                <li><a class="hover:text-emerald-700" href="{{ route('legal.show', 'mentions-legales') }}">Mentions légales</a></li>
                <li><a class="hover:text-emerald-700" href="{{ route('legal.show', 'confidentialite') }}">Confidentialité</a></li>
            </ul>
        </div>
        <div>
            <p class="font-semibold mb-2">Explorer</p>
            <ul class="space-y-1">
                <li><a class="hover:text-emerald-700" href="{{ route('tastebox') }}">TasteBox</a></li>
                <li><a class="hover:text-emerald-700" href="{{ route('about') }}">À propos</a></li>
                <li><a class="hover:text-emerald-700" href="{{ route('contact.create') }}">Contact</a></li>
                <li><a class="hover:text-emerald-700" href="{{ route('certificates.verify') }}">Vérifier un certificat</a></li>
            </ul>
        </div>
    </div>
</footer>
