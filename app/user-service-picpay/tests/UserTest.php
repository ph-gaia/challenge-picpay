<?php

abstract class UserTest extends Laravel\Lumen\Testing\TestCase
{

    public function test_can_create_seller()
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
            'social_name' => 'Henrique Company',
            'fantasy_name' => 'Henrique',
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

}
