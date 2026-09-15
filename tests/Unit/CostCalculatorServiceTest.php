<?php

namespace Tests\Unit;

use App\Services\CostCalculatorService;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CostCalculatorServiceTest extends TestCase
{
    #[Test]
    public function it_calculates_unit_cost_and_suggested_price(): void
    {
        $service = new CostCalculatorService;
        $result = $service->calculate([
            'product_name' => 'Attiéké bol',
            'ingredients' => [
                ['name' => 'Manioc', 'quantity' => 2, 'unit_cost' => 500],
                ['name' => 'Huile', 'quantity' => 0.1, 'unit_cost' => 2000],
            ],
            'labor_hours' => 1,
            'labor_rate' => 1000,
            'overhead' => 200,
            'packaging' => 100,
            'yield_units' => 10,
            'margin_percent' => 50,
        ]);

        $this->assertSame(1200.0, $result['ingredients_total']);
        $this->assertSame(1000.0, $result['labor_total']);
        $this->assertSame(2500.0, $result['total_cost']);
        $this->assertSame(250.0, $result['unit_cost']);
        $this->assertSame(375.0, $result['suggested_price']);
        $this->assertSame(125.0, $result['unit_margin']);
    }
}
