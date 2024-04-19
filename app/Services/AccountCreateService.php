<?php

namespace App\Services;

use App\Services\IpkissAPI;
use Illuminate\Support\Arr;
use App\Exceptions\AccountAlreadyExistsException;

/**
 * Handle account creation
 */
class AccountCreateService
{
    public function __construct(private IpkissAPI $api) {}

    public function handle(array $data)
    {
        $id     = Arr::get($data, 'id');
        $amount = Arr::get($data, 'amount');

        if ($this->api->accountExists($id)) {
            throw new AccountAlreadyExistsException(
                sprintf('The account %s already exists and cannot be created', $id)
            );
        }

        return $this->api->deposit($id, $amount);
    }
}