<?php

declare(strict_types=1);

namespace Laravel\Cashier\Tests\Mollie;

use Laravel\Cashier\Mollie\Contracts\GetMolliePayment;
use Mollie\Api\Resources\Payment;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

class GetMolliePaymentTest extends BaseMollieInteraction
{
    #[Test]
    #[Group('mollie_integration')]
    public function testExecute()
    {
        /** @var GetMolliePayment $action */
        $action = $this->app->make(GetMolliePayment::class);
        $id = $this->getMandatePaymentID();
        $result = $action->execute($id);

        $this->assertInstanceOf(Payment::class, $result);
        $this->assertEquals($id, $result->id);
    }
}
