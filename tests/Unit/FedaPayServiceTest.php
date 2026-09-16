<?php

namespace Tests\Unit;

use App\Services\FedaPayService;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FedaPayServiceTest extends TestCase
{
    #[Test]
    public function environment_respects_fedapay_mode(): void
    {
        config(['foodlab.payments.fedapay.mode' => 'live']);
        $this->assertSame('live', app(FedaPayService::class)->environment());

        config(['foodlab.payments.fedapay.mode' => 'sandbox']);
        $this->assertSame('sandbox', app(FedaPayService::class)->environment());
    }

    #[Test]
    public function extract_payment_refs_from_approved_event(): void
    {
        $event = (object) [
            'name' => 'transaction.approved',
            'entity' => (object) [
                'id' => 42,
                'status' => 'approved',
                'custom_metadata' => (object) ['payment_id' => '7'],
            ],
        ];

        $refs = app(FedaPayService::class)->extractPaymentRefs($event);

        $this->assertTrue($refs['approved']);
        $this->assertSame('7', $refs['payment_id']);
        $this->assertSame('42', $refs['external_id']);
    }

    #[Test]
    public function parse_webhook_without_secret_decodes_json(): void
    {
        config(['foodlab.payments.fedapay.webhook_secret' => null]);
        $payload = json_encode(['name' => 'transaction.approved', 'entity' => ['id' => 1]]);
        $event = app(FedaPayService::class)->parseWebhook($payload, null);
        $this->assertSame('transaction.approved', $event->name);
    }
}
