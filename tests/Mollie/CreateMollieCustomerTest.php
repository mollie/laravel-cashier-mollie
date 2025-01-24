<?php

declare(strict_types=1);

namespace Laravel\Cashier\Tests\Mollie;

use Laravel\Cashier\Mollie\Contracts\CreateMollieCustomer;
use Mollie\Api\Resources\Customer;

class CreateMollieCustomerTest extends BaseMollieInteraction
{
    protected $interactWithMollieAPI = true;

    /**
     * @test
     *
     * @group mollie_integration
     */
    public function test_execute()
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
