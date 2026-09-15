<?php

namespace App\Services;

class CostCalculatorService
{
    /**
     * Calcule le coût de revient d'un produit alimentaire.
     *
     * @param  array{
     *   product_name?: string,
     *   ingredients: list<array{name?: string, quantity: float|int|string, unit_cost: float|int|string}>,
     *   labor_hours?: float|int|string,
     *   labor_rate?: float|int|string,
     *   overhead?: float|int|string,
     *   packaging?: float|int|string,
     *   yield_units?: float|int|string,
     *   margin_percent?: float|int|string
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
        ];
    }
}
