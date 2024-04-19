<?php
namespace App\Http\Controllers;

use Illuminate\Http\{Request, Response};

class AccountController extends Controller
{
    
    public function create(Request $request): Response
    {
        $balance = [
            'id' => $request->post('id'),
            'balance' => $request->post('amount')
        ];

        return response($balance, 200);
    }

    public function balance(string $id, Request $request): Response
    {
        $balance = [
            'id' => $id,
            'balance' => 0
        ];

        return response($balance, 200);
    }

    public function withdraw(string $id, Request $request): Response
    {
        $balance = [
            'id' => $id,
            'balance' => 0,
            'withdrawn_amount' => 0
        ];

        return response($balance, 200);
    }

    public function transfer(string $id, Request $request): Response
    {
        $balance = [
            'amount' => 10,
            'origin' => [
                'id' => $id,
                'balance' => 0,
            ],
            'destination' => [
                'id' => $id,
                'balance' => 0,
            ],
        ];

        return response($balance, 200);
    }

    public function deposit(string $id, Request $request): Response
    {
        $balance = [
            'id' => $id,
            'balance' => $request->post('amount')
        ];

        return response($balance, 200);
    }
}