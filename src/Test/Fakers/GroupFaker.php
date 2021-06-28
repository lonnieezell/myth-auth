<?php namespace Myth\Auth\Test\Fakers;

use Faker\Generator;
use Myth\Auth\Authorization\GroupModel;
use stdClass;

class GroupFaker extends GroupModel
{
	/**
	 * Faked data for Fabricator.
	 *
	 * @param Generator $faker
	 *
	 * @return stdClass
	 */
	public function fake(Generator &$faker): stdClass
	{
		return (object) [
            'name'        => $faker->word,
            'description' => $faker->sentence,
		];
	}
}
