<?php

declare(strict_types=1);

namespace Laravel\Cashier\Tests\Mollie;

use Laravel\Cashier\Mollie\Contracts\CreateMollieCustomer;
use Mollie\Api\Resources\Customer;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

class CreateMollieCustomerTest extends BaseMollieInteraction
{
    protected $interactWithMollieAPI = true;

    #[Test]
    #[Group('mollie_integration')]
    public function testExecute()
    {
        /** @var CreateMollieCustomer $action */
        $action = $this->app->make(CreateMollieCustomer::class);

        $result = $action->execute([
            'email' => 'john@example.com',
            'name' => 'John Doe',
        ]);

        $this->assertInstanceOf(Customer::class, $result);
        $this->assertEquals('john@example.com', $result->email);
        $this->assertEquals('John Doe', $result->name);
    }
}
