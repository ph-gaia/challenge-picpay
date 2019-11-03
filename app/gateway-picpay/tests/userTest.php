<?php

abstract class userTest extends Laravel\Lumen\Testing\TestCase
{

    public function test_can_get_users()
    {
        $response = $this->get('/api/users');
        $response->assertResponseStatus(200)
            ->assertJsonStructure([
                'data' => ['full_name', 'cpf', 'phone_number', 'email', 'account_type']
            ]);
    }

    public function test_can_create_users()
    {
        $data = [
            'name' => 'Paulo Henrique Gaia',
            'cpf' => '01134669275',
            'phone' => '91992854548',
            'email' => 'phcgaia11@yahoo.com.br',
            'accountType' => 'CUSTOMER',
            'username' => 'phenrique',
            'password' => 'admin123'
        ];

        $response = $this->post('/api/users', $data);

        $response->assertResponseStatus(201);
    }

    public function test_can_update_users()
    {
        $user = factory(Users::class)->create();
        $data = [
            'name' => 'Paulo Henrique Gaia',
            'cpf' => '01134669275',
            'phone' => '91992854548',
            'email' => 'phcgaia11@yahoo.com.br',
        ];
        $this->put('/api/users/' . $user->id, $data)
            ->assertResponseStatus(200);
    }

    public function test_can_delete_users()
    {
        $user = factory(Users::class)->create();
        $this->delete('/api/users/' . $user->id)
            ->assertResponseStatus(204);
    }
}
