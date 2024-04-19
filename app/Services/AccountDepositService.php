<?php

namespace App\Services;

use App\Services\IpkissAPI;

/**
 * Handle account deposit
 */
class AccountDepositService
{
    public function __construct(private IpkissAPI $api) {}

    public function handle(string $id, int $amount): array
    {
        $this->api->balance($id);

        return $this->api->deposit($id, $amount);
    }
}