<?php
namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Requests\{
    AccountCreateRequest,
    AccountWithdrawRequest,
    AccountTransferRequest,
    AccountDepositRequest
};
use App\Services\{
    IpkissAPI,
    AccountCreateService,
    AccountWithdrawalService,
    AccountTransferService,
    AccountDepositService
};
use Illuminate\Support\Arr;
use \Exception;

/**
 * API account controller
 */
class AccountController extends Controller
{
    /**
     * POST /api/account/reset
     * Reset all accounts
     *
     * @return Response
     */
    public function reset(): Response
    {
        $data = resolve(IpkissAPI::class)->reset();

        return response(Arr::get($data, 'response'), Arr::get($data, 'status', 404));
    }

    /**
     * POST /api/account/create
     * Create new account
     *
     * @param AccountCreateRequest $request
     * @return Response
     */
    public function create(AccountCreateRequest $request): Response
    {
        try {
            $balance = resolve(AccountCreateService::class)
                ->handle($request->validated());

            return response($balance, 200);
        } catch (Exception $ex) {
            return response([$ex->getMessage()], 404);
        }
    }

    /**
     * GET /api/account/:id/balance
     * Retrieves information about account balance
     *
     * @param string $id
     * @return Response
     */
    public function balance(string $id): Response
    {
        try {
            $balance = resolve(IpkissAPI::class)
                ->balance($id);

            return response($balance, 200);
        } catch (Exception $ex) {
            return response([$ex->getMessage()], 404);
        }
    }

    /**
     * POST /api/account/:id/withdraw
     * Creates a withdrawal for target account
     *
     * @param string $id
     * @param AccountWithdrawRequest $request
     * @return Response
     */
    public function withdraw(string $id, AccountWithdrawRequest $request): Response
    {
        try {
            $balance = resolve(AccountWithdrawalService::class)
                ->handle($id, $request->amount);
    
            return response($balance, 200);
        } catch (Exception $ex) {
            return response([$ex->getMessage()], 404);
        }
    }

    /**
     * POST /api/account/:id/transfer
     * Creates a transfer between accounts
     *
     * @param string $id
     * @param AccountTransferRequest $request
     * @return Response
     */
    public function transfer(string $id, AccountTransferRequest $request): Response
    {
        try {
            $balance = resolve(AccountTransferService::class)
                ->handle($id, $request->validated());

            return response($balance, 200);
        } catch (Exception $ex) {
            return response([$ex->getMessage()], 404);
        }
    }

    /**
     * POST /api/account/:id/deposit
     * Creates a deposit for existing account
     *
     * @param string $id
     * @param AccountDepositRequest $request
     * @return Response
     */
    public function deposit(string $id, AccountDepositRequest $request): Response
    {
        try {
            $balance = resolve(AccountDepositService::class)
                ->handle($id, $request->amount);

            return response($balance, 200);
        } catch (Exception $ex) {
            return response([$ex->getMessage()], 404);
        }
    }
}