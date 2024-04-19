<?php

namespace App\Services;

use App\Services\IpkissAPI;
use Illuminate\Support\Arr;
use App\Exceptions\InsuficientBalanceAmountException;

/**
 * Handle account withdrawals
 */
class AccountWithdrawalService
{
    public function __construct(private IpkissAPI $api) {}

    public function handle(string $id, int $amount)
    {
        $balance = $this->api->balance($id);
        $current = Arr::get($balance, 'balance');

        if ($amount > $current) {
            throw new InsuficientBalanceAmountException(
                sprintf('The account %s does not have enough balance to make this withdrawal. Current balance: %d', $id, $current)
            );
        }

        return $this->api->withdraw($id, $amount);
    }
}