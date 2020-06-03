<?php namespace Myth\Auth\Test\Fakers;

use Faker\Generator;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\UserModel;

class UserFaker extends UserModel
{
	/**
	 * Faked data for Fabricator.
	 *
	 * @param Generator $faker
	 *
	 * @return Myth\Auth\Entities\User
	 */
	public function fake(Generator &$faker): User
	{
		return new User([
			'email'         => $faker->email,
			'username'      => implode('_', $faker->words),
			'password'      =>  bin2hex(random_bytes(16)),
		]);
	}
}
