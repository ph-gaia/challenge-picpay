<?php

abstract class TransactionsTest extends Laravel\Lumen\Testing\TestCase
{
    public function test_can_create_transactions()
    {
        $data = [
            'payee' => 1,
            'payer' => 2,
            'transactionDate' => '2019-11-05',
            'value' => 50
        ];
        $user = \App\Models\Transactions::create($data);

        $this->seeInDatabase('transactions', ['payee' => 1, 'payer' => 2]);
    }
}
