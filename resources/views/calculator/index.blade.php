<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Calculateur de coût de revient</h2></x-slot>
    <div class="max-w-4xl mx-auto py-8 px-4 grid lg:grid-cols-2 gap-8">
        <form method="POST" action="{{ route('calculator.store') }}" class="bg-white border rounded-xl p-6 space-y-4" x-data="{ lines: [{name:'',quantity:1,unit_cost:0}] }">
            @csrf
            <div>
                <x-input-label value="Titre de la session" />
                <x-text-input name="title" class="w-full mt-1" />
            </div>
            <div>
                <x-input-label value="Produit" />
                <x-text-input name="product_name" class="w-full mt-1" required />
            </div>
            <div>
                <p class="font-medium mb-2">Ingrédients</p>
                <template x-for="(line, index) in lines" :key="index">
                    <div class="grid grid-cols-3 gap-2 mb-2">
                        <input type="text" :name="`ingredients[${index}][name]`" x-model="line.name" placeholder="Nom" class="rounded-md border-stone-300 text-sm" />
                        <input type="number" step="0.01" :name="`ingredients[${index}][quantity]`" x-model="line.quantity" placeholder="Qté" class="rounded-md border-stone-300 text-sm" required />
                        <input type="number" step="0.01" :name="`ingredients[${index}][unit_cost]`" x-model="line.unit_cost" placeholder="Coût u." class="rounded-md border-stone-300 text-sm" required />
                    </div>
                </template>
                <button type="button" class="text-sm text-emerald-700" @click="lines.push({name:'',quantity:1,unit_cost:0})">+ Ajouter un ingrédient</button>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div><x-input-label value="Heures main d'œuvre" /><x-text-input type="number" step="0.01" name="labor_hours" class="w-full mt-1" value="0" /></div>
                <div><x-input-label value="Taux horaire" /><x-text-input type="number" step="0.01" name="labor_rate" class="w-full mt-1" value="0" /></div>
                <div><x-input-label value="Frais généraux" /><x-text-input type="number" step="0.01" name="overhead" class="w-full mt-1" value="0" /></div>
                <div><x-input-label value="Emballage" /><x-text-input type="number" step="0.01" name="packaging" class="w-full mt-1" value="0" /></div>
                <div><x-input-label value="Nombre de portions" /><x-text-input type="number" step="0.01" name="yield_units" class="w-full mt-1" value="1" required /></div>
                <div><x-input-label value="Marge %" /><x-text-input type="number" step="0.01" name="margin_percent" class="w-full mt-1" value="30" /></div>
            </div>
            <div class="border-t pt-4 space-y-3">
                <p class="font-medium text-sm text-slate-700">Rentabilité & marché</p>
                <div class="grid grid-cols-2 gap-3">
                    <div><x-input-label value="Coûts fixes (seuil)" /><x-text-input type="number" step="0.01" name="fixed_costs" class="w-full mt-1" placeholder="= frais généraux si vide" /></div>
                    <div><x-input-label value="Prix de vente unitaire" /><x-text-input type="number" step="0.01" name="selling_price" class="w-full mt-1" placeholder="= prix suggéré si vide" /></div>
                    <div class="col-span-2"><x-input-label value="Prix marché (comparaison)" /><x-text-input type="number" step="0.01" name="market_price" class="w-full mt-1" placeholder="Optionnel" /></div>
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
                        <p class="text-xs text-slate-500">{{ $s->created_at->format('d/m/Y H:i') }} — {{ number_format($s->results['unit_cost'] ?? 0, 2, ',', ' ') }} / unité</p>
                    </a>
                @empty
                    <p class="text-sm text-slate-500">Aucune session pour le moment.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
