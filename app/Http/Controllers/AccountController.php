<?php
namespace App\Http\Controllers;

use Illuminate\Http\{Request, Response};
use \Exception;

use App\Services\IpkissAPI;

class AccountController extends Controller
{
    private ?IpkissAPI $service = null;

    public function __construct()
    {
        $this->service = resolve(IpkissAPI::class);
    }

    public function create(Request $request): Response
    {
        try {
            $balance = $this
                ->service
                ->deposit($request->post('id'), $request->post('amount'));
    
            return response($balance, 200);
        } catch (Exception $ex) {
            return response([$ex->getMessage()], 404);
        }
    }

    public function balance(string $id, Request $request): Response
    {
        try {
            $balance = [
                'id' => $id,
                'balance' => $this->service->balance($id)
            ];
    
            return response($balance, 200);
        } catch (Exception $ex) {
            return response([$ex->getMessage()], 404);
        }
    }

    public function withdraw(string $id, Request $request): Response
    {
        try {
            $balance = $this
                ->service
                ->withdraw($request->post('id'), $request->post('amount'));
    
            return response($balance, 200);
        } catch (Exception $ex) {
            return response([$ex->getMessage()], 404);
        }
    }

    public function transfer(string $id, Request $request): Response
    {
        try {
            $balance = $this
                ->service
                ->transfer($id, $request->post('destination'), $request->post('amount'));
    
            return response($balance, 200);
        } catch (Exception $ex) {
            return response([$ex->getMessage()], 404);
        }
    }

    public function deposit(string $id, Request $request): Response
    {
        try {
            $balance = $this
                ->service
                ->deposit($id, $request->post('amount'));
    
            return response($balance, 200);
        } catch (Exception $ex) {
            return response([$ex->getMessage()], 404);
        }
    }
}