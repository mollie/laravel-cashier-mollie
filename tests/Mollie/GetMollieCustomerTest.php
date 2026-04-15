<?php

declare(strict_types=1);

namespace Laravel\Cashier\Tests\Mollie;

use Laravel\Cashier\Mollie\Contracts\GetMollieCustomer;
use Mollie\Api\Resources\Customer;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

class GetMollieCustomerTest extends BaseMollieInteraction
{
    #[Test]
    #[Group('mollie_integration')]
    public function testExecute()
    {
        /** @var GetMollieCustomer $action */
        $action = $this->app->make(GetMollieCustomer::class);
        $id = $this->getMandatedCustomerId();
        $result = $action->execute($id);

        $this->assertInstanceOf(Customer::class, $result);
        $this->assertEquals($id, $result->id);
    }
}
