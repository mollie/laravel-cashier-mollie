<?php

declare(strict_types=1);

namespace Laravel\Cashier\Plan\Contracts;

interface PlanRepository
{
    /**
     * @return null|Plan
     */
    public static function find(string $name);

    /**
     * @return Plan
     */
    public static function findOrFail(string $name);
}
