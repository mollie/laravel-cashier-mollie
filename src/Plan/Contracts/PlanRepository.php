<?php

declare(strict_types=1);

namespace Laravel\Cashier\Plan\Contracts;

interface PlanRepository
{
    /**
     * @return null|\Laravel\Cashier\Plan\Contracts\Plan
     */
    public static function find(string $name);

    /**
     * @return \Laravel\Cashier\Plan\Contracts\Plan
     */
    public static function findOrFail(string $name);
}
