<?php

namespace Myth\Auth\Test\Fakers;

use Faker\Generator;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\UserModel;

class UserFaker extends UserModel
{
    /**
     * Faked data for Fabricator.
     */
    public function fake(Generator &$faker): User
    {
        return new User([
            'email'    => $faker->email,
            'username' => $faker->userName,
            'password' => bin2hex(random_bytes(16)),
        ]);
    }
}
