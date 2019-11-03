<?php

class UserTest extends Laravel\Lumen\Testing\TestCase
{

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

        $response->assertStatus(201);
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
            ->assertStatus(200);
    }

    public function test_can_delete_users()
    {
        $user = factory(Users::class)->create();
        $this->delete('/api/users/' . $user->id)
            ->assertStatus(204);
    }
}
