<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Calculateur de coût de revient</h2></x-slot>
    <div class="max-w-5xl mx-auto py-8 px-4 grid lg:grid-cols-2 gap-8"
         x-data="{
            lines: [{name:'',quantity:1,unit_cost:0}],
            applyLoss: false,
            marginMode: 'markup_on_cost',
            sector: 'agroalimentaire',
            maturity: 'idee'
         }">
        <form method="POST" action="{{ route('calculator.store') }}" class="bg-white border rounded-xl p-6 space-y-5">
            @csrf
            <div>
                <p class="font-semibold text-emerald-800 mb-1">Votre profil</p>
                <p class="text-xs text-slate-500 mb-3">Adapte le contexte à votre secteur et votre maturité.</p>
                <div class="grid sm:grid-cols-2 gap-3">
                    <div>
                        <x-input-label value="Secteur" />
                        <select name="sector" x-model="sector" class="mt-1 w-full rounded-md border-stone-300 text-sm">
                            <option value="agroalimentaire">Agroalimentaire</option>
                            <option value="cosmetique">Cosmétique</option>
                            <option value="restauration">Restauration</option>
                            <option value="autre">Autre / Service</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label value="Niveau de maturité" />
                        <select name="maturity_level" x-model="maturity" class="mt-1 w-full rounded-md border-stone-300 text-sm">
                            <option value="idee">Idée / Prototype</option>
                            <option value="demarrage">Démarrage</option>
                            <option value="croissance">Croissance</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label value="Devise" />
                        <x-text-input name="currency" class="w-full mt-1" value="FCFA" />
                    </div>
                    <div>
                        <x-input-label value="Unité vendue" />
                        <x-text-input name="unit_label" class="w-full mt-1" placeholder="pot, kg, portion…" />
                    </div>
                </div>
            </div>

            <div>
                <x-input-label value="Titre de la session" />
                <x-text-input name="title" class="w-full mt-1" />
            </div>
            <div>
                <x-input-label value="Produit" />
                <x-text-input name="product_name" class="w-full mt-1" required />
            </div>

            <div>
                <p class="font-medium mb-1">Matières premières</p>
                <p class="text-xs text-slate-500 mb-2">Coût des intrants pour un lot complet. Le « lot » est la quantité d'unités obtenue à chaque fabrication.</p>
                <template x-for="(line, index) in lines" :key="index">
                    <div class="grid grid-cols-3 gap-2 mb-2">
                        <input type="text" :name="`ingredients[${index}][name]`" x-model="line.name" placeholder="Nom" class="rounded-md border-stone-300 text-sm" />
                        <input type="number" step="0.01" :name="`ingredients[${index}][quantity]`" x-model="line.quantity" placeholder="Qté" class="rounded-md border-stone-300 text-sm" required />
                        <input type="number" step="0.01" :name="`ingredients[${index}][unit_cost]`" x-model="line.unit_cost" placeholder="Coût u." class="rounded-md border-stone-300 text-sm" required />
                    </div>
                </template>
                <button type="button" class="text-sm text-emerald-700" @click="lines.push({name:'',quantity:1,unit_cost:0})">+ Ajouter un ingrédient</button>

                <label class="mt-3 flex items-start gap-2 text-sm">
                    <input type="checkbox" name="apply_yield_loss" value="1" x-model="applyLoss" class="mt-1 rounded border-stone-300 text-emerald-600">
                    <span>Prendre en compte une perte de rendement à la transformation</span>
                </label>
                <div class="mt-2" x-show="applyLoss" x-cloak>
                    <x-input-label value="Perte de rendement %" />
                    <x-text-input type="number" step="0.1" name="yield_loss_percent" class="w-full mt-1" value="5" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div><x-input-label value="Heures main d'œuvre" /><x-text-input type="number" step="0.01" name="labor_hours" class="w-full mt-1" value="0" /></div>
                <div><x-input-label value="Taux horaire" /><x-text-input type="number" step="0.01" name="labor_rate" class="w-full mt-1" value="0" /></div>
                <div><x-input-label value="Charges fixes (lot)" /><x-text-input type="number" step="0.01" name="overhead" class="w-full mt-1" value="0" /></div>
                <div><x-input-label value="Emballage" /><x-text-input type="number" step="0.01" name="packaging" class="w-full mt-1" value="0" /></div>
                <div><x-input-label value="Taille du lot (unités)" /><x-text-input type="number" step="0.01" name="yield_units" class="w-full mt-1" value="1" required /></div>
            </div>
            <p class="text-xs text-slate-500">Même sans salaire versé, fixez-vous un taux horaire réaliste : sinon votre coût de revient sous-estime la vraie charge.</p>

            <div class="border-t pt-4 space-y-3">
                <p class="font-medium text-sm text-slate-700">Marge, distribution & marché</p>
                <div>
                    <x-input-label value="Mode de calcul de la marge" />
                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-3 gap-2 text-sm">
                        <label class="flex items-center gap-2 rounded-lg border px-3 py-2 cursor-pointer" :class="marginMode==='markup_on_cost' ? 'border-emerald-500 bg-emerald-50' : 'border-stone-200'">
                            <input type="radio" name="margin_mode" value="markup_on_cost" x-model="marginMode" class="text-emerald-600"> % sur le coût
                        </label>
                        <label class="flex items-center gap-2 rounded-lg border px-3 py-2 cursor-pointer" :class="marginMode==='margin_on_price' ? 'border-emerald-500 bg-emerald-50' : 'border-stone-200'">
                            <input type="radio" name="margin_mode" value="margin_on_price" x-model="marginMode" class="text-emerald-600"> % sur le prix
                        </label>
                        <label class="flex items-center gap-2 rounded-lg border px-3 py-2 cursor-pointer" :class="marginMode==='fixed_amount' ? 'border-emerald-500 bg-emerald-50' : 'border-stone-200'">
                            <input type="radio" name="margin_mode" value="fixed_amount" x-model="marginMode" class="text-emerald-600"> Montant fixe
                        </label>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <x-input-label>
                            <span x-text="marginMode==='fixed_amount' ? 'Marge (montant)' : 'Marge %'"></span>
                        </x-input-label>
                        <x-text-input type="number" step="0.01" name="margin_value" class="w-full mt-1" value="30" />
                    </div>
                    <div><x-input-label value="Prix marché (optionnel)" /><x-text-input type="number" step="0.01" name="market_price" class="w-full mt-1" placeholder="Concurrent" /></div>
                    <div><x-input-label value="Coûts fixes (seuil)" /><x-text-input type="number" step="0.01" name="fixed_costs" class="w-full mt-1" placeholder="= charges si vide" /></div>
                    <div><x-input-label value="Prix de vente unitaire" /><x-text-input type="number" step="0.01" name="selling_price" class="w-full mt-1" placeholder="= prix suggéré si vide" /></div>
                </div>
            </div>
            <x-primary-button>Calculer & enregistrer</x-primary-button>
        </form>
        <div>
            <h3 class="font-semibold mb-3">Sessions récentes</h3>
            <div class="space-y-2">
                @forelse($sessions as $s)
                    <a href="{{ route('calculator.show', $s) }}" class="block bg-white border rounded-lg px-4 py-3 hover:border-emerald-400">
                        <p class="font-medium">{{ $s->title }}</p>
                        <p class="text-xs text-slate-500">{{ $s->created_at->format('d/m/Y H:i') }} — {{ number_format($s->results['unit_cost'] ?? 0, 2, ',', ' ') }} {{ $s->results['currency'] ?? 'FCFA' }} / unité</p>
                    </a>
                @empty
                    <p class="text-sm text-slate-500">Aucune session pour le moment.</p>
                @endforelse
            </div>
            <div class="mt-6 rounded-xl bg-emerald-50 border border-emerald-100 p-4 text-sm text-emerald-900">
                <p class="font-semibold mb-1">Astuce FoodLab</p>
                <p>Sélectionnez votre secteur et votre niveau pour contextualiser vos calculs. Activez la perte de rendement si la transformation réduit le volume utilisable.</p>
            </div>
        </div>
    </div>
</x-app-layout>
