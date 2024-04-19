<?php

namespace App\Services;

use App\Services\IpkissAPI;
use Illuminate\Support\Arr;
use App\Exceptions\{
    InsuficientBalanceAmountException,
    InvalidTransferException
};

/**
 * Handle balance transfers between accounts
 */
class AccountTransferService
{
    public function __construct(private IpkissAPI $api) {}

    public function handle(string $id, array $data)
    {
        $amount        = Arr::get($data, 'amount');
        $destinationId = Arr::get($data, 'destination');

        if ($id == $destinationId) {
            throw new InvalidTransferException(
                'The origin account cannot be the same as the destination account when transferring balances'
            );
        }

        $this->api->balance($destinationId);

        $origin        = $this->api->balance($id);
        $originBalance = Arr::get($origin, 'balance');

        if ($amount > $originBalance) {
            throw new InsuficientBalanceAmountException(
                sprintf('The account %s does not have enough balance to make this transfer. Current balance: %d', $id, $originBalance)
            );
        }

        return $this->api->transfer($id, $destinationId, $amount);
    }
}