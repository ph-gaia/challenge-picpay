<?php

class TransactionsTest extends Laravel\Lumen\Testing\TestCase
{

    public function test_can_create_transactions()
    {
        $data = [
            'payee' => 1,
            'payer' => 2,
            'transactionDate' => '2019-11-05',
            'value' => 50
        ];

        $response = $this->post('/transaction', $data);

        $response->assertStatus(200);
    }

    public function test_can_not_create_transactions()
    {
        $data = [
            'payee' => 1,
            'payer' => 2,
            'transactionDate' => '2019-11-05',
            'value' => 110
        ];

        $response = $this->post('/transaction', $data);

        $response->assertStatus(400);
    }
}
