<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use App\Exceptions\{
    InvalidAccountException,
    AccountDepositException,
    InvalidWithdrawException,
    InvalidTransferException
};

/**
 * Interface for {{https://ipkiss.pragmazero.com/}} api
 */
class IpkissAPI 
{
    /**
     * @var PendingRequest|null
     */
    private ?PendingRequest $client = null;

    public function __construct()
    {
        $this->client = Http::baseUrl('https://ipkiss.pragmazero.com/')
            ->contentType('application/json')
            ->acceptJson();
    }

    /**
     * Get balance for given account
     *
     * @param string $id
     * @return integer
     * @throws InvalidAccountException if invalid or non-existent account
     */
    public function balance(string $id): int
    {
        $response = $this->client->get('balance', [
            'account_id' => $id
        ]);

        if (! $response->successful()) {
            throw new InvalidAccountException(
                sprintf('The account ID %s is invalid or non-existent', $id)
            );
        }

        return intval($response->body());
    }

    /**
     * Creates a new account or deposit for existing account and returns its balance
     *
     * @param string $id
     * @param integer $amount
     * @return array {id: string, balance: int}
     * @throws AccountDepositException if response not successful
     */
    public function deposit(string $id, int $amount): array
    {
        $response = $this->client->post('event', [
            'type'        => 'deposit',
            'destination' => $id,
            'amount'      => $amount
        ]);

        if (! $response->successful()) {
            throw new AccountDepositException(
                sprintf('An error occurred while trying to create a deposit for account %s', $id)
            );
        }

        $body = $response->json();
        return Arr::get($body, 'destination', []);
    }

    /**
     * Creates an withdrawal for a given accoutn and returns its balance
     *
     * @param string $id
     * @param integer $amount
     * @return array {id: string, balance: int, amount_withdrawn: int}
     * @throws InvalidWithdrawException if response not successful
     */
    public function withdraw(string $id, int $amount): array
    {
        $response = $this->client->post('event', [
            'type'   => 'withdraw',
            'origin' => $id,
            'amount' => $amount
        ]);

        if (! $response->successful()) {
            throw new InvalidWithdrawException(
                sprintf('An error occurred while trying to create a withdraw for account %s', $id)
            );
        }

        $body = $response->json();
        $balance = Arr::get($body, 'origin', []);
        Arr::set($balance, 'amount_withdrawn', $amount);

        return $balance;
    }

    /**
     * Creates a transfer from $origin account to $destination account
     *
     * @param string $origin
     * @param string $destination
     * @param integer $amount
     * @return array {origin: {id: string, balance: int}, destination: {id: string, balance: int}, amount_transferred: int} 
     * @throws InvalidTransferException if an error occurs during the transfer
     */
    public function transfer(string $origin, string $destination, int $amount): array
    {
        $response = $this->client->post('event', [
            'type'        => 'transfer',
            'origin'      => $origin,
            'amount'      => $amount,
            'destination' => $destination
        ]);

        if (! $response->successful()) {
            throw new InvalidTransferException(
                sprintf('An error occurred while trying to transfer the amount %d from account %s to %s', $amount, $destination, $origin)
            );
        }

        $body = $response->json();
        $balance = Arr::only($body, ['origin', 'destination'], []);
        Arr::set($balance, 'amount_transferred', $amount);

        return $balance;
    }
}