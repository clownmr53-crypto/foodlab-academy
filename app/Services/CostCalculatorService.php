<?php

namespace App\Services;

class CostCalculatorService
{
    /**
     * Calcule le coût de revient, seuil de rentabilité et écart marché.
     *
     * @param  array{
     *   product_name?: string,
     *   ingredients: list<array{name?: string, quantity: float|int|string, unit_cost: float|int|string}>,
     *   labor_hours?: float|int|string,
     *   labor_rate?: float|int|string,
     *   overhead?: float|int|string,
     *   packaging?: float|int|string,
     *   yield_units?: float|int|string,
     *   margin_percent?: float|int|string,
     *   market_price?: float|int|string|null,
     *   fixed_costs?: float|int|string|null,
     *   selling_price?: float|int|string|null
     * }  $inputs
     * @return array<string, mixed>
     */
    public function calculate(array $inputs): array
    {
        $ingredients = $inputs['ingredients'] ?? [];
        $ingredientLines = [];
        $ingredientsTotal = 0.0;

        foreach ($ingredients as $line) {
            $qty = (float) ($line['quantity'] ?? 0);
            $unitCost = (float) ($line['unit_cost'] ?? 0);
            $lineTotal = round($qty * $unitCost, 2);
            $ingredientsTotal += $lineTotal;
            $ingredientLines[] = [
                'name' => (string) ($line['name'] ?? 'Ingrédient'),
                'quantity' => $qty,
                'unit_cost' => $unitCost,
                'total' => $lineTotal,
            ];
        }

        $laborHours = (float) ($inputs['labor_hours'] ?? 0);
        $laborRate = (float) ($inputs['labor_rate'] ?? 0);
        $laborTotal = round($laborHours * $laborRate, 2);
        $overhead = round((float) ($inputs['overhead'] ?? 0), 2);
        $packaging = round((float) ($inputs['packaging'] ?? 0), 2);
        $yieldUnits = max(1.0, (float) ($inputs['yield_units'] ?? 1));
        $marginPercent = (float) ($inputs['margin_percent'] ?? 30);

        $totalCost = round($ingredientsTotal + $laborTotal + $overhead + $packaging, 2);
        $unitCost = round($totalCost / $yieldUnits, 2);
        $suggestedPrice = round($unitCost * (1 + ($marginPercent / 100)), 2);
        $unitMargin = round($suggestedPrice - $unitCost, 2);

        // Variable cost per unit ≈ matières + MO + emballage (hors frais généraux)
        $variableTotal = round($ingredientsTotal + $laborTotal + $packaging, 2);
        $variableUnitCost = round($variableTotal / $yieldUnits, 2);

        // Fixed costs: explicit override or overhead
        $fixedCosts = isset($inputs['fixed_costs']) && $inputs['fixed_costs'] !== null && $inputs['fixed_costs'] !== ''
            ? round((float) $inputs['fixed_costs'], 2)
            : $overhead;

        $sellingPrice = isset($inputs['selling_price']) && $inputs['selling_price'] !== null && $inputs['selling_price'] !== ''
            ? round((float) $inputs['selling_price'], 2)
            : $suggestedPrice;

        $contributionMargin = round($sellingPrice - $variableUnitCost, 2);
        $breakEvenUnits = null;
        $breakEvenRevenue = null;
        if ($contributionMargin > 0) {
            $breakEvenUnits = (int) ceil($fixedCosts / $contributionMargin);
            $breakEvenRevenue = round($breakEvenUnits * $sellingPrice, 2);
        }

        $marketPrice = null;
        $marketDeltaVsCost = null;
        $marketDeltaVsSuggested = null;
        if (isset($inputs['market_price']) && $inputs['market_price'] !== null && $inputs['market_price'] !== '') {
            $marketPrice = round((float) $inputs['market_price'], 2);
            $marketDeltaVsCost = round($marketPrice - $unitCost, 2);
            $marketDeltaVsSuggested = round($marketPrice - $suggestedPrice, 2);
        }

        // Chart data (cost breakdown + break-even illustration)
        $chartBreakdown = [
            'labels' => ['Ingrédients', 'Main d\'œuvre', 'Frais généraux', 'Emballage'],
            'values' => [
                round($ingredientsTotal, 2),
                $laborTotal,
                $overhead,
                $packaging,
            ],
        ];

        return [
            'product_name' => (string) ($inputs['product_name'] ?? 'Produit'),
            'ingredient_lines' => $ingredientLines,
            'ingredients_total' => round($ingredientsTotal, 2),
            'labor_total' => $laborTotal,
            'overhead' => $overhead,
            'packaging' => $packaging,
            'total_cost' => $totalCost,
            'yield_units' => $yieldUnits,
            'unit_cost' => $unitCost,
            'margin_percent' => $marginPercent,
            'suggested_price' => $suggestedPrice,
            'unit_margin' => $unitMargin,
            'fixed_costs' => $fixedCosts,
            'variable_unit_cost' => $variableUnitCost,
            'selling_price' => $sellingPrice,
            'contribution_margin' => $contributionMargin,
            'break_even_units' => $breakEvenUnits,
            'break_even_revenue' => $breakEvenRevenue,
            'market_price' => $marketPrice,
            'market_delta_vs_cost' => $marketDeltaVsCost,
            'market_delta_vs_suggested' => $marketDeltaVsSuggested,
            'chart_breakdown' => $chartBreakdown,
        ];
    }
}
