<?php

abstract class UserTest extends Laravel\Lumen\Testing\TestCase
{
    public function testCanCreateConsumer()
    {
        $data = [
            'full_name' => 'Paulo Henrique Gaia',
            'cpf' => '01134669275',
            'phone_number' => '91992854548',
            'email' => 'phcgaia11@yahoo.com.br',
            'account_type' => 'CUSTOMER',
            'username' => 'phenrique',
            'password' => 'admin123'
        ];

        $response = $this->json('POST', route('users.create'), $data);

        $response->assertStatus(201);
    }

    public function testCanCreateSeller()
    {
        $dataUser = [
            'full_name' => 'Paulo Henrique Gaia',
            'cpf' => '01134669275',
            'phone' => '91992854548',
            'email' => 'phcgaia11@yahoo.com.br',
            'accountType' => 'SELLER',
        ];
        $user = \App\Models\Users::create($dataUser);

        $dataSeller = [
            'socialName' => 'Henrique Company',
            'fantasyName' => 'Henrique',
            'cnpj' => '39609552000137',
            'users_id' => $user->id
        ];
        $seller = \App\Models\Seller::create($dataSeller);

        $dataAuth = [
            "username" =>  'phenrique',
            "password" => password_hash('admin123', PASSWORD_DEFAULT),
            "active" => 'ENABLE',
            'users_id' => $user->id
        ];
        $auth = \App\Models\Authentication::create($dataAuth);

        $this->seeInDatabase('users', ['full_name' => 'Paulo Henrique Gaia']);
        $this->seeInDatabase('authentication', ['users_id' => $user->id, 'username' => 'phenrique']);
        $this->seeInDatabase('seller', ['users_id' => $user->id, 'cnpj' => '39609552000137']);
    }

    public function testCanUpdateUser()
    {
        $user = factory(Users::class)->create();
        $data = [
            'full_name' => 'Paulo Henrique Gaia',
            'cpf' => '01134669275',
            'phone' => '91992854548',
            'email' => 'phcgaia11@yahoo.com.br',
        ];
        $this->put(route('user.update', $user->id), $data)
            ->assertStatus(200)
            ->assertJson($data);
    }

    public function testCanDeleteUser()
    {
        $user = factory(Users::class)->create();
        $this->delete(route('user.destroy', $user->id))
            ->assertStatus(204);
    }
}
